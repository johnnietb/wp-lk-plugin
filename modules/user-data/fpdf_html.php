<?php
if(!@require("fpdf.php")) throw new Exception("Failed to include 'fpdf.php'");

class PDF_HTML extends FPDF {
  //variables of html parser
  var $B;
  var $I;
  var $U;
  var $HREF;
  var $fontList;
  var $issetfont;
  var $issetcolor;

  //function hex2dec
  //returns an associative array (keys: R,G,B) from
  //a hex html code (e.g. #3FE5AA)
  function hex2dec($couleur = "#000000"){
      $R = substr($couleur, 1, 2);
      $rouge = hexdec($R);
      $V = substr($couleur, 3, 2);
      $vert = hexdec($V);
      $B = substr($couleur, 5, 2);
      $bleu = hexdec($B);
      $tbl_couleur = array();
      $tbl_couleur['R']=$rouge;
      $tbl_couleur['V']=$vert;
      $tbl_couleur['B']=$bleu;
      return $tbl_couleur;
  }

  //conversion pixel -> millimeter at 72 dpi
  function px2mm($px){
      return $px*25.4/96;
  }

  function txtentities($html){
      $trans = get_html_translation_table(HTML_ENTITIES);
      $trans = array_flip($trans);
      return strtr($html, $trans);
  }
  ////////////////////////////////////

  function PDF_HTML($orientation='P', $unit='mm', $format='A4')
  {
      //Call parent constructor
      //$this->PDF_HTML($orientation,$unit,$format);
      parent::__construct($orientation,$unit,$format);
      //Initialization
      $this->B=0;
      $this->I=0;
      $this->U=0;
      $this->HREF='';
      $this->ALIGN='';
      $this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
      $this->issetfont=false;
      $this->issetcolor=false;
      $this->DefaultFontSize = 11;

  }

  function WriteHTML($html)
  {
      //HTML parser
      $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><ul><li><div><td><tr><table><h1><h2><h3><h4>"); //supprime tous les tags sauf ceux reconnus
      $html=str_replace("\n",' ',$html); //replace carriage returns by spaces
      $html=str_replace("\t",'',$html); //replace carriage returns by spaces
      $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
      foreach($a as $i=>$e)
      {
          if($i%2==0)
          {
              //Text
              if($this->HREF)
                  $this->PutLink($this->HREF,$e);
              elseif($this->ALIGN == 'center')
                  $this->Cell(0, 5, stripslashes($this->txtentities($e)), 0, 1, 'C');
              else
                  $this->Write(5,stripslashes($this->txtentities($e)));
          }
          else
          {
              //Tag
              if($e[0]=='/')
                  $this->CloseTag(strtoupper(substr($e,1)));
              else
              {
                  //Extract attributes
                  $a2=explode(' ',$e);
                  $tag=strtoupper(array_shift($a2));
                  $attr=array();
                  foreach($a2 as $v)
                  {
                      if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                          $attr[strtoupper($a3[1])]=$a3[2];
                  }
                  $this->OpenTag($tag,$attr);
              }
          }
      }
  }

  function OpenTag($tag, $attr)
  {
      //Opening tag
      $this->ALIGN = '';
      switch($tag){
          case 'STRONG':
              $this->SetStyle('B',true);
              break;
          case 'EM':
              $this->SetStyle('I',true);
              break;
          case 'B':
          case 'I':
          case 'U':
              $this->SetStyle($tag,true);
              break;
          case 'A':
              $this->HREF=$attr['HREF'];
              break;
          case 'DIV': case 'P':
              $this->SetFontSize($this->DefaultFontSize);
              $this->ALIGN=$attr["ALIGN"];
              break;
          case 'H1':
              $this->SetFontSize(30);
              $this->ALIGN=$attr["ALIGN"];
              break;
          case 'H2':
              $this->SetFontSize(24);
              $this->ALIGN=$attr["ALIGN"];
              break;
          case 'H3':
              $this->SetFontSize(18);
              $this->ALIGN=$attr["ALIGN"];
              break;
          case 'H4':
              $this->SetFontSize(14);
              $this->ALIGN=$attr["ALIGN"];
              break;
          case 'IMG':
              if( isset($attr['SRC']) ) {
                  $ins = 10;
                  if ($attr["ALIGN"] == "center") {
                    list($width, $height) = getimagesize($attr['SRC']);
                    $ins = $this->px2mm(intval((800-$width)/2));
                  }
                  $this->Image($attr['SRC'],$ins, null, 0);
              }
              break;
          case 'BLOCKQUOTE':
          case 'BR':
              $this->Ln(5);
              break;
          // case 'P':
          //     break;
          case 'FONT':
              if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                  $coul=$this->hex2dec($attr['COLOR']);
                  $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                  $this->issetcolor=true;
              }
              if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                  $this->SetFont(strtolower($attr['FACE']));
                  $this->issetfont=true;
              }
              break;
              case 'TABLE': // TABLE-BEGIN
              if( !empty($attr['BORDER']) ) $this->tableborder=$attr['BORDER'];
              else $this->tableborder=0;
              break;
          case 'TR': //TR-BEGIN
              break;
          case 'TD': // TD-BEGIN
              if( !empty($attr['WIDTH']) ) $this->tdwidth=($attr['WIDTH']/4);
              else $this->tdwidth=40; // Set to your own width if you need bigger fixed cells
              if( !empty($attr['HEIGHT']) ) $this->tdheight=($attr['HEIGHT']/6);
              else $this->tdheight=6; // Set to your own height if you need bigger fixed cells
              if( !empty($attr['ALIGN']) ) {
                  $align=$attr['ALIGN'];
                  if($align=='LEFT') $this->tdalign='L';
                  if($align=='CENTER') $this->tdalign='C';
                  if($align=='RIGHT') $this->tdalign='R';
              }
              else $this->tdalign='L'; // Set to your own
              if( !empty($attr['BGCOLOR']) ) {
                  $coul=hex2dec($attr['BGCOLOR']);
                      $this->SetFillColor($coul['R'],$coul['G'],$coul['B']);
                      $this->tdbgcolor=true;
                  }
              $this->tdbegin=true;
              break;

          case 'HR':
              if( !empty($attr['WIDTH']) )
                  $Width = $attr['WIDTH'];
              else
                  $Width = $this->w - $this->lMargin-$this->rMargin;
              $x = $this->GetX();
              $y = $this->GetY();
              $this->SetLineWidth(0.2);
              $this->Line($x,$y,$x+$Width,$y);
              $this->SetLineWidth(0.2);
              $this->Ln(1);
              break;
      }
  }

  function CloseTag($tag)
  {
      //Closing tag
      if($tag=='TD') { // TD-END
        $this->tdbegin=false;
        $this->tdwidth=0;
        $this->tdheight=0;
        $this->tdalign="L";
        $this->tdbgcolor=false;
      }
      if($tag=='TR') { // TR-END
          $this->Ln();
      }
      if($tag=='TABLE') { // TABLE-END
          //$this->Ln();
          $this->tableborder=0;
      }
      if($tag=='STRONG')
          $tag='B';
      if($tag=='EM')
          $tag='I';
      if($tag=='B' || $tag=='I' || $tag=='U')
          $this->SetStyle($tag,false);
      if($tag=='A')
          $this->HREF='';
      if($tag=='FONT'){
          if ($this->issetcolor==true) {
              $this->SetTextColor(0);
          }
          if ($this->issetfont) {
              $this->SetFont('arial');
              $this->issetfont=false;
          }
      }
      if($tag=='LI' || $tag=='P' || $tag=='DIV' || $tag=='IMG' || $tag=='H1' || $tag=='H2' || $tag=='H3' || $tag=='H4'){
          $this->Ln();
          $this->Ln();
      }
  }

  function SetStyle($tag, $enable)
  {
      //Modify style and select corresponding font
      $this->$tag+=($enable ? 1 : -1);
      $style='';
      foreach(array('B','I','U') as $s)
      {
          if($this->$s>0)
              $style.=$s;
      }
      $this->SetFont('',$style);
  }

  function PutLink($URL, $txt)
  {
      //Put a hyperlink
      $this->SetTextColor(0,0,255);
      $this->SetStyle('U',true);
      $this->Write(5,$txt,$URL);
      $this->SetStyle('U',false);
      $this->SetTextColor(0);
  }

}//end of class
?>
