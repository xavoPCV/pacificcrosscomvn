<?php
defined('_JEXEC') or die;
$this->data = explode('|$|', $this->data );
$true = ($this->data[1]/($this->data[1]+$this->data[2]))*100;
$false = ($this->data[2]/($this->data[1]+$this->data[2]))*100;
?>
<style>
    .canvasjs-chart-credit{
        display:none;
    }
</style>


<?php   if( $_GET['idpoll'] == '' ) { ?>
<h2>Thank you for your vote!</h2>
<?php }else { ?>
         <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
        <script type="text/javascript"> if (typeof(jQuery) !== "undefined") { jQuery.noConflict(); }  </script>
<?php } ?>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.canvasjs.min.js"></script>
<script type="text/javascript"> 
window.onload = function() { 
	jQuery("#chartContainer").CanvasJSChart({ 
		title: { 
			text: "<?php echo $this->data[0]; ?>",
			fontSize: 24
		}, 
		axisY: { 
			title: "Vote in %" 
		}, 
		legend :{ 
			verticalAlign: "center", 
			horizontalAlign: "right" 
		}, 
		data: [ 
		{ 
			type: "pie", 
			showInLegend: true, 
			toolTipContent: "{label} <br/> {y} %", 
			indexLabel: "{y} %", 
			dataPoints: [ 
				{ label: "True",  y: <?php echo $true; ?>, legendText: "True"}, 
				{ label: "False",    y: <?php echo $false; ?>, legendText: "False"  }, 
				
			] 
		} 
		] 
	}); 
} 
</script> 

<div id="chartContainer" style="width: 100%; height: 300px"></div>