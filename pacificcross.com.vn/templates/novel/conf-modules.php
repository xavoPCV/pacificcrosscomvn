<?php 

	if( $this->countModules('header-1 or header-2 or header-3 or header-4 or header-5 or header-6')) : 
	if( $this->countModules('header-1') ) $l[0] = 0;
	if( $this->countModules('header-2') ) $l[1] = 1;
	if( $this->countModules('header-3') ) $l[2] = 2;
	if( $this->countModules('header-4') ) $l[3] = 3;
	if( $this->countModules('header-5') ) $l[4] = 4;
	if( $this->countModules('header-6') ) $l[5] = 5; 
	$header1 = count($l); 
	if ($header1 == 1) $lg1class = "span12";
	if ($header1 == 2) $lg1class = "span6";
	if ($header1 == 3) $lg1class = "span4";
	if ($header1 == 4) $lg1class = "span3";
	if ($header1 == 5) { $lg1class = "span2"; $lg1class5w = "17.9%"; };
	if ($header1 == 6) $lg1class = "span2";
	endif; 

	if( $this->countModules('top-1 or top-2 or top-3 or top-4 or top-5 or top-6')) : 
	if( $this->countModules('top-1') ) $a[0] = 0;
	if( $this->countModules('top-2') ) $a[1] = 1;
	if( $this->countModules('top-3') ) $a[2] = 2;
	if( $this->countModules('top-4') ) $a[3] = 3;
	if( $this->countModules('top-5') ) $a[4] = 4;
	if( $this->countModules('top-6') ) $a[5] = 5; 
	$topmodules1 = count($a); 
	if ($topmodules1 == 1) $tm1class = "span12";
	if ($topmodules1 == 2) $tm1class = "span6";
	if ($topmodules1 == 3) $tm1class = "span4";
	if ($topmodules1 == 4) $tm1class = "span3";
	if ($topmodules1 == 5) { $tm1class = "span2"; $tm1class5w = "17.9%"; };
	if ($topmodules1 == 6) $tm1class = "span2";
	endif; 
	
	if( $this->countModules('top-7 or top-8 or top-9 or top-10 or top-11 or top-12')) : 
	if( $this->countModules('top-7') ) $b[0] = 0;
	if( $this->countModules('top-8') ) $b[1] = 1;
	if( $this->countModules('top-9') ) $b[2] = 2;
	if( $this->countModules('top-10') ) $b[3] = 3;
	if( $this->countModules('top-11') ) $b[4] = 4;
	if( $this->countModules('top-12') ) $b[5] = 5; 
	$topmodules2 = count($b); 
	if ($topmodules2 == 1) $tm2class = "span12";
	if ($topmodules2 == 2) $tm2class = "span6";
	if ($topmodules2 == 3) $tm2class = "span4";
	if ($topmodules2 == 4) $tm2class = "span3";
	if ($topmodules2 == 5) { $tm2class = "span2"; $tm2class5w = "17.9%"; };
	if ($topmodules2 == 6) $tm2class = "span2";
	endif; 
	
	if( $this->countModules('bottom-1 or bottom-2 or bottom-3 or bottom-4 or bottom-5 or bottom-6')) :
	if( $this->countModules('bottom-1') ) $c[0] = 0; 
	if( $this->countModules('bottom-2') ) $c[1] = 1; 
	if( $this->countModules('bottom-3') ) $c[2] = 2; 
	if( $this->countModules('bottom-4') ) $c[3] = 3; 
	if( $this->countModules('bottom-5') ) $c[4] = 4; 
	if( $this->countModules('bottom-6') ) $c[5] = 5; 
	$botmodules = count($c); 
	if ($botmodules == 1) $bmclass = "span12";
	if ($botmodules == 2) $bmclass = "span6";
	if ($botmodules == 3) $bmclass = "span4";
	if ($botmodules == 4) $bmclass = "span3";
	if ($botmodules == 5) { $bmclass = "span2"; $bmclass5w = "17.7%"; };
	if ($botmodules == 6) $bmclass = "span2";
	endif; 
	
	if( $this->countModules('bottom-7 or bottom-8 or bottom-9 or bottom-10 or bottom-11 or bottom-12')) :
	if( $this->countModules('bottom-7') ) $cb[0] = 0; 
	if( $this->countModules('bottom-8') ) $cb[1] = 1; 
	if( $this->countModules('bottom-9') ) $cb[2] = 2; 
	if( $this->countModules('bottom-10') ) $cb[3] = 3; 
	if( $this->countModules('bottom-11') ) $cb[4] = 4; 
	if( $this->countModules('bottom-12') ) $cb[5] = 5; 
	$botmodules2 = count($cb); 
	if ($botmodules2 == 1) $bm2class = "span12";
	if ($botmodules2 == 2) $bm2class = "span6";
	if ($botmodules2 == 3) $bm2class = "span4";
	if ($botmodules2 == 4) $bm2class = "span3";
	if ($botmodules2 == 5) { $bm2class = "span2"; $bm2class5w = "17.7%"; };
	if ($botmodules2 == 6) $bm2class = "span2";
	endif; 

	if( $this->countModules('bottom-e or bottom-f or bottom-g or bottom-h or bottom-i or bottom-j')) :
	if( $this->countModules('bottom-e') ) $cp[0] = 0; 
	if( $this->countModules('bottom-f') ) $cp[1] = 1; 
	if( $this->countModules('bottom-g') ) $cp[2] = 2; 
	if( $this->countModules('bottom-h') ) $cp[3] = 3; 
	if( $this->countModules('bottom-i') ) $cp[4] = 4; 
	if( $this->countModules('bottom-j') ) $cp[5] = 5; 
	$bottoms = count($cp); 
	if ($bottoms == 1) $cpclass = "span12";
	if ($bottoms == 2) $cpclass = "span6";
	if ($bottoms == 3) $cpclass = "span4";
	if ($bottoms == 4) $cpclass = "span3";
	if ($bottoms == 5) { $cpclass = "span2"; $cpclass5w = "17.7%"; };
	if ($bottoms == 6) $cpclass = "span2";
	endif; 		

	if( $this->countModules('footer-1 or footer-2 or footer-3 or footer-4 or footer-5 or footer-6')) :
	if( $this->countModules('footer-1') ) $f[0] = 0; 
	if( $this->countModules('footer-2') ) $f[1] = 1; 
	if( $this->countModules('footer-3') ) $f[2] = 2; 
	if( $this->countModules('footer-4') ) $f[3] = 3; 
	if( $this->countModules('footer-5') ) $f[4] = 4; 
	if( $this->countModules('footer-6') ) $f[5] = 5; 
	$footers = count($f); 
	if ($footers == 1) $fclass = "span12";
	if ($footers == 2) $fclass = "span6";
	if ($footers == 3) $fclass = "span4";
	if ($footers == 4) $fclass = "span3";
	if ($footers == 5) { $fclass = "span2"; $fclass5w = "17.7%"; };
	if ($footers == 6) $fclass = "span2";
	endif; 	
	
	
	if( $this->countModules('top-a or top-b or top-c or top-d')) :
	if( $this->countModules('top-a') ) $d[0] = 0; 
	if( $this->countModules('top-b') ) $d[1] = 1; 
	if( $this->countModules('top-c') ) $d[2] = 2; 
	if( $this->countModules('top-d') ) $d[3] = 3; 
	$topamodules = count($d); 
	if ($topamodules == 1) $tpaclass = "span12";
	if ($topamodules == 2) $tpaclass = "span6";
	if ($topamodules == 3) $tpaclass = "span4";
	if ($topamodules == 4) $tpaclass = "span3";
	endif; 
	
	if( $this->countModules('bottom-a or bottom-b or bottom-c or bottom-d')) :
	if( $this->countModules('bottom-a') ) $e[0] = 0; 
	if( $this->countModules('bottom-b') ) $e[1] = 1; 
	if( $this->countModules('bottom-c') ) $e[2] = 2; 
	if( $this->countModules('bottom-d') ) $e[3] = 3; 
	$bottomamodules = count($e); 
	if ($bottomamodules == 1) $bmaclass = "span12";
	if ($bottomamodules == 2) $bmaclass = "span6";
	if ($bottomamodules == 3) $bmaclass = "span4";
	if ($bottomamodules == 4) $bmaclass = "span3";
	endif; 
	
	if( $this->countModules('top-right-1 or top-right-2 or position-6 or right or bottom-right-1 or bottom-right-2') && $this->countModules('top-left-1 or top-left-2 or position-7 or left or bottom-left-1 or bottom-left-2')  ) : $mcols = 'span6'; 
	elseif( $this->countModules('top-right-1 or top-right-2 or position-6 or right or bottom-right-1 or bottom-right-2') && !$this->countModules('top-left-1 or top-left-2 or position-7 or left or bottom-left-1 or bottom-left-2')  ) : $mcols = 'span9'; 
	elseif( !$this->countModules('top-right-1 or top-right-2 or position-6 or right or bottom-right-1 or bottom-right-2') && $this->countModules('top-left-1 or top-left-2 or position-7 or left or bottom-left-1 or bottom-left-2')  ) : $mcols = 'span9'; else : $mcols = 'span12'; endif;	
	
	
?>