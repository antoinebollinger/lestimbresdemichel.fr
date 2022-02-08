<?php

require('fpdf.php');

// Stream handler to read from global variables
class VariableStream
{
    private $varname;
    private $position;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url['host'];
        if(!isset($GLOBALS[$this->varname]))
        {
            trigger_error('Global variable '.$this->varname.' does not exist', E_USER_WARNING);
            return false;
        }
        $this->position = 0;
        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_seek($offset, $whence)
    {
        if($whence==SEEK_SET)
        {
            $this->position = $offset;
            return true;
        }
        return false;
    }
    
    function stream_stat()
    {
        return array();
    }
}

class PDF_Gradients extends FPDF{

	function Circle($x, $y, $r, $style='D') {
		$this->Ellipse($x,$y,$r,$r,$style);
	}
	
	function Ellipse($x, $y, $rx, $ry, $style='D') {
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$lx=4/3*(M_SQRT2-1)*$rx;
		$ly=4/3*(M_SQRT2-1)*$ry;
		$k=$this->k;
		$h=$this->h;
		$this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
			($x+$rx)*$k,($h-$y)*$k,
			($x+$rx)*$k,($h-($y-$ly))*$k,
			($x+$lx)*$k,($h-($y-$ry))*$k,
			$x*$k,($h-($y-$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$lx)*$k,($h-($y-$ry))*$k,
			($x-$rx)*$k,($h-($y-$ly))*$k,
			($x-$rx)*$k,($h-$y)*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$rx)*$k,($h-($y+$ly))*$k,
			($x-$lx)*$k,($h-($y+$ry))*$k,
			$x*$k,($h-($y+$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
			($x+$lx)*$k,($h-($y+$ry))*$k,
			($x+$rx)*$k,($h-($y+$ly))*$k,
			($x+$rx)*$k,($h-$y)*$k,
			$op));
	}	

    var $gradients = array();

    function LinearGradient($x, $y, $w, $h, $col1=array(), $col2=array(), $coords=array(0,0,1,0)){
        $this->Clip($x,$y,$w,$h);
        $this->Gradient(2,$col1,$col2,$coords);
    }

    function RadialGradient($x, $y, $w, $h, $col1=array(), $col2=array(), $coords=array(0.5,0.5,0.5,0.5,1)){
        $this->Clip($x,$y,$w,$h);
        $this->Gradient(3,$col1,$col2,$coords);
    }

    function CoonsPatchMesh($x, $y, $w, $h, $col1=array(), $col2=array(), $col3=array(), $col4=array(), $coords=array(0.00,0.0,0.33,0.00,0.67,0.00,1.00,0.00,1.00,0.33,1.00,0.67,1.00,1.00,0.67,1.00,0.33,1.00,0.00,1.00,0.00,0.67,0.00,0.33), $coords_min=0, $coords_max=1){
        $this->Clip($x,$y,$w,$h);        
        $n = count($this->gradients)+1;
        $this->gradients[$n]['type']=6; //coons patch mesh
        //check the coords array if it is the simple array or the multi patch array
        if(!isset($coords[0]['f'])){
            //simple array -> convert to multi patch array
            if(!isset($col1[1]))
                $col1[1]=$col1[2]=$col1[0];
            if(!isset($col2[1]))
                $col2[1]=$col2[2]=$col2[0];
            if(!isset($col3[1]))
                $col3[1]=$col3[2]=$col3[0];
            if(!isset($col4[1]))
                $col4[1]=$col4[2]=$col4[0];
            $patch_array[0]['f']=0;
            $patch_array[0]['points']=$coords;
            $patch_array[0]['colors'][0]['r']=$col1[0];
            $patch_array[0]['colors'][0]['g']=$col1[1];
            $patch_array[0]['colors'][0]['b']=$col1[2];
            $patch_array[0]['colors'][1]['r']=$col2[0];
            $patch_array[0]['colors'][1]['g']=$col2[1];
            $patch_array[0]['colors'][1]['b']=$col2[2];
            $patch_array[0]['colors'][2]['r']=$col3[0];
            $patch_array[0]['colors'][2]['g']=$col3[1];
            $patch_array[0]['colors'][2]['b']=$col3[2];
            $patch_array[0]['colors'][3]['r']=$col4[0];
            $patch_array[0]['colors'][3]['g']=$col4[1];
            $patch_array[0]['colors'][3]['b']=$col4[2];
        }
        else{
            //multi patch array
            $patch_array=$coords;
        }
        $bpcd=65535; //16 BitsPerCoordinate
        //build the data stream
        $this->gradients[$n]['stream']='';
        for($i=0;$i<count($patch_array);$i++){
            $this->gradients[$n]['stream'].=chr($patch_array[$i]['f']); //start with the edge flag as 8 bit
            for($j=0;$j<count($patch_array[$i]['points']);$j++){
                //each point as 16 bit
                $patch_array[$i]['points'][$j]=(($patch_array[$i]['points'][$j]-$coords_min)/($coords_max-$coords_min))*$bpcd;
                if($patch_array[$i]['points'][$j]<0) $patch_array[$i]['points'][$j]=0;
                if($patch_array[$i]['points'][$j]>$bpcd) $patch_array[$i]['points'][$j]=$bpcd;
                $this->gradients[$n]['stream'].=chr(floor($patch_array[$i]['points'][$j]/256));
                $this->gradients[$n]['stream'].=chr(floor($patch_array[$i]['points'][$j]%256));
            }
            for($j=0;$j<count($patch_array[$i]['colors']);$j++){
                //each color component as 8 bit
                $this->gradients[$n]['stream'].=chr($patch_array[$i]['colors'][$j]['r']);
                $this->gradients[$n]['stream'].=chr($patch_array[$i]['colors'][$j]['g']);
                $this->gradients[$n]['stream'].=chr($patch_array[$i]['colors'][$j]['b']);
            }
        }
        //paint the gradient
        $this->_out('/Sh'.$n.' sh');
        //restore previous Graphic State
        $this->_out('Q');
    }

    function Clip($x,$y,$w,$h){
        //save current Graphic State
        $s='q';
        //set clipping area
        $s.=sprintf(' %.2F %.2F %.2F %.2F re W n', $x*$this->k, ($this->h-$y)*$this->k, $w*$this->k, -$h*$this->k);
        //set up transformation matrix for gradient
        $s.=sprintf(' %.3F 0 0 %.3F %.3F %.3F cm', $w*$this->k, $h*$this->k, $x*$this->k, ($this->h-($y+$h))*$this->k);
        $this->_out($s);
    }

    function Gradient($type, $col1, $col2, $coords){
        $n = count($this->gradients)+1;
        $this->gradients[$n]['type']=$type;
        if(!isset($col1[1]))
            $col1[1]=$col1[2]=$col1[0];
        $this->gradients[$n]['col1']=sprintf('%.3F %.3F %.3F',($col1[0]/255),($col1[1]/255),($col1[2]/255));
        if(!isset($col2[1]))
            $col2[1]=$col2[2]=$col2[0];
        $this->gradients[$n]['col2']=sprintf('%.3F %.3F %.3F',($col2[0]/255),($col2[1]/255),($col2[2]/255));
        $this->gradients[$n]['coords']=$coords;
        //paint the gradient
        $this->_out('/Sh'.$n.' sh');
        //restore previous Graphic State
        $this->_out('Q');
    }

    function _putshaders(){
        foreach($this->gradients as $id=>$grad){  
            if($grad['type']==2 || $grad['type']==3){
                $this->_newobj();
                $this->_out('<<');
                $this->_out('/FunctionType 2');
                $this->_out('/Domain [0.0 1.0]');
                $this->_out('/C0 ['.$grad['col1'].']');
                $this->_out('/C1 ['.$grad['col2'].']');
                $this->_out('/N 1');
                $this->_out('>>');
                $this->_out('endobj');
                $f1=$this->n;
            }
            
            $this->_newobj();
            $this->_out('<<');
            $this->_out('/ShadingType '.$grad['type']);
            $this->_out('/ColorSpace /DeviceRGB');
            if($grad['type']=='2'){
                $this->_out(sprintf('/Coords [%.3F %.3F %.3F %.3F]',$grad['coords'][0],$grad['coords'][1],$grad['coords'][2],$grad['coords'][3]));
                $this->_out('/Function '.$f1.' 0 R');
                $this->_out('/Extend [true true] ');
                $this->_out('>>');
            }
            elseif($grad['type']==3){
                //x0, y0, r0, x1, y1, r1
                //at this time radius of inner circle is 0
                $this->_out(sprintf('/Coords [%.3F %.3F 0 %.3F %.3F %.3F]',$grad['coords'][0],$grad['coords'][1],$grad['coords'][2],$grad['coords'][3],$grad['coords'][4]));
                $this->_out('/Function '.$f1.' 0 R');
                $this->_out('/Extend [true true] ');
                $this->_out('>>');
            }
            elseif($grad['type']==6){
                $this->_out('/BitsPerCoordinate 16');
                $this->_out('/BitsPerComponent 8');
                $this->_out('/Decode[0 1 0 1 0 1 0 1 0 1]');
                $this->_out('/BitsPerFlag 8');
                $this->_out('/Length '.strlen($grad['stream']));
                $this->_out('>>');
                $this->_putstream($grad['stream']);
            }
            $this->_out('endobj');
            $this->gradients[$id]['id']=$this->n;
        }
    }

    function _putresourcedict(){
        parent::_putresourcedict();
        $this->_out('/Shading <<');
        foreach($this->gradients as $id=>$grad)
             $this->_out('/Sh'.$id.' '.$grad['id'].' 0 R');
        $this->_out('>>');
    }

    function _putresources(){
        $this->_putshaders();
        parent::_putresources();
    }
    function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct($orientation, $unit, $size);
        // Register var stream protocol
        stream_wrapper_register('var', 'VariableStream');
    }

    function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        // Display the image contained in $data
        $v = 'img'.md5($data);
        $GLOBALS[$v] = $data;
        $a = getimagesize('var://'.$v);
        if(!$a)
            $this->Error('Invalid image data');
        $type = substr(strstr($a['mime'],'/'),1);
        $this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
        unset($GLOBALS[$v]);
    }

    function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        // Display the GD image associated with $im
        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        $this->MemImage($data, $x, $y, $w, $h, $link);
    }
}
?>