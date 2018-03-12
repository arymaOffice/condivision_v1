
<?php

$where_count = str_replace('WHERE ','', $tipologia_main);

$bassa = mk_count($tabella,"priorita_contatto = 0 AND ".$where_count);
$media = mk_count($tabella,"priorita_contatto = 1 AND ".$where_count);
$alta = mk_count($tabella,"priorita_contatto = 2 AND ".$where_count);


$totale = $bassa+$media+$alta;


?>


<style>
.xe-widget {
	margin-bottom: 10px;
position: relative;
width: 22%;
margin-right: 1%;
float: left;
min-height: 137px;

 }
.xe-widget a { color: white; }
.xe-widget.xe-progress-counter.xe-progress-counter-turquoise {
    background-color: #00b19d;
}
.xe-widget.xe-counter-block.xe-counter-block-orange, .xe-widget.xe-progress-counter.xe-counter-block-orange {
    background: #f7aa47;
}
.xe-widget.xe-counter-block.xe-counter-block-orange .xe-upper, .xe-widget.xe-progress-counter.xe-counter-block-orange .xe-upper {
    background: #f7aa47;
}

.xe-widget.xe-counter-block.xe-counter-block-red, .xe-widget.xe-progress-counter.xe-counter-block-red {
    background: #cc3f44;
}
.xe-widget.xe-counter-block.xe-counter-block-red .xe-upper, .xe-widget.xe-progress-counter.xe-counter-block-red .xe-upper {
    background: #cc3f44;
}


.xe-widget.xe-progress-counter {
    position: relative;
    color: #fff;
    background: #68b828;
    overflow: hidden;
}

.xe-widget.xe-progress-counter.xe-progress-counter-turquoise .xe-background {
    color: #fff;
}
.xe-background {
    position: absolute;
    left: -40%;
    bottom: -10%;
    color: #fff;
    font-size: 150px;
    zoom: 1;
    -webkit-opacity: .1;
    -moz-opacity: .1;
    opacity: .1;
    -ms-filter: alpha(Opacity=10);
    filter: alpha(opacity=10);
}
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.xe-widget.xe-progress-counter {
    color: #fff;
}
.xe-widget.xe-counter-block, .xe-widget.xe-progress-counter {
    color: #fff;
}
.page-container {
    border-collapse: collapse;
    border-spacing: 0;
}

.xe-widget.xe-progress-counter.xe-progress-counter-turquoise .xe-upper {
    background-color: transparent;
}
.xe-widget.xe-counter-block .xe-upper, .xe-widget.xe-progress-counter .xe-upper {
    margin: 0;
    border-spacing: 0;
    border: 0;
    background: #68b828;
}
.xe-widget.xe-counter, .xe-widget.xe-counter-block .xe-upper, .xe-widget.xe-progress-counter .xe-upper {
    background: #fff;
    padding: 0 28px;
    line-height: 1;
    display: table;
    width: 100%;
   
}
.xe-widget.xe-counter-block .xe-upper .xe-icon, .xe-widget.xe-progress-counter .xe-upper .xe-icon {
    text-align: center;
}
.xe-widget.xe-counter-block .xe-upper .xe-icon, .xe-widget.xe-counter-block .xe-upper .xe-label, .xe-widget.xe-progress-counter .xe-upper .xe-icon, .xe-widget.xe-progress-counter .xe-upper .xe-label {
    padding-bottom: 0;
}
.xe-widget.xe-counter .xe-icon, .xe-widget.xe-counter-block .xe-upper .xe-icon, .xe-widget.xe-progress-counter .xe-upper .xe-icon {
    width: 1%;
}
.xe-widget.xe-counter .xe-icon, .xe-widget.xe-counter .xe-label, .xe-widget.xe-counter-block .xe-upper .xe-icon, .xe-widget.xe-counter-block .xe-upper .xe-label, .xe-widget.xe-progress-counter .xe-upper .xe-icon, .xe-widget.xe-progress-counter .xe-upper .xe-label {
    display: table-cell;
    vertical-align: middle;
    padding: 18px;
}
.xe-widget.xe-progress-counter .xe-upper .xe-icon i {
    font-size: 28px;
    background: 0 0;
}
.xe-widget.xe-counter .xe-icon + .xe-label, .xe-widget.xe-counter-block .xe-upper .xe-icon + .xe-label, .xe-widget.xe-progress-counter .xe-upper .xe-icon + .xe-label {
 padding-left: 0;
text-align: left;
margin-left: 29%;
display: inline-block;
}
.xe-widget.xe-counter-block .xe-upper .xe-icon, .xe-widget.xe-counter-block .xe-upper .xe-label, .xe-widget.xe-progress-counter .xe-upper .xe-icon, .xe-widget.xe-progress-counter .xe-upper .xe-label {
    padding-bottom: 0;
}
.xe-widget.xe-counter .xe-icon, .xe-widget.xe-counter .xe-label, .xe-widget.xe-counter-block .xe-upper .xe-icon, .xe-widget.xe-counter-block .xe-upper .xe-label, .xe-widget.xe-progress-counter .xe-upper .xe-icon, .xe-widget.xe-progress-counter .xe-upper .xe-label {
    display: table-cell;
    vertical-align: middle;
    padding: 10px 0 10px 3px;
}
.xe-widget.xe-progress-counter .xe-upper .xe-label .num {
    font-size: 20px;
}
.xe-widget.xe-counter-block .xe-upper .xe-label .num, .xe-widget.xe-progress-counter .xe-upper .xe-label .num {
    color: #fff;
}

.xe-widget.xe-counter .xe-label span, .xe-widget.xe-counter-block .xe-upper .xe-label span, .xe-widget.xe-progress-counter .xe-upper .xe-label span {
    display: block;
    font-style: normal;
    font-size: 10px;
    text-transform: uppercase;
    color: #979898;
    margin-top: 5px;
}
.xe-widget.xe-progress-counter .xe-upper .xe-label span {
    padding: 0;
    padding-bottom: 5px;
}
.xe-widget.xe-counter-block .xe-upper .xe-label span, .xe-widget.xe-progress-counter .xe-upper .xe-label span {
    color: rgba(255,255,255,.7);
}
.xe-widget.xe-counter .xe-label .num, .xe-widget.xe-counter-block .xe-upper .xe-label .num, .xe-widget.xe-progress-counter .xe-upper .xe-label .num {
    display: block;
    font-size: 28px;
    font-weight: 400;
    color: white;
}
.xe-progress {
    height: 2px;
    position: relative;
    background: rgba(0,0,0,.1);
    margin: 0 30px;
   
}
.xe-progress .xe-progress-fill {
    position: absolute;
    display: block;
    left: 0;
    top: 0;
    bottom: 0;
    background: #fff;
}
.xe-widget.xe-counter-block .xe-lower, .xe-widget.xe-progress-counter .xe-lower {
    padding: 15px 30px;
}
.xe-widget.xe-counter-block .xe-lower span, .xe-widget.xe-progress-counter .xe-lower span {
    color: rgba(255,255,255,.7);
}
.xe-widget.xe-counter-block .xe-lower span, .xe-widget.xe-counter-block .xe-lower strong, .xe-widget.xe-progress-counter .xe-lower span, .xe-widget.xe-progress-counter .xe-lower strong {
    font-size: 10px;
    text-transform: uppercase;
    display: block;
}
.xe-widget.xe-progress-counter .xe-lower strong {
    font-weight: 400;
}
.xe-widget.xe-counter-block .xe-lower span, .xe-widget.xe-counter-block .xe-lower strong, .xe-widget.xe-progress-counter .xe-lower span, .xe-widget.xe-progress-counter .xe-lower strong {
    font-size: 10px;
    text-transform: uppercase;
    display: block;
}
.xe-widget.xe-counter-block .xe-upper .xe-icon i, .xe-widget.xe-progress-counter .xe-upper .xe-icon i {
    font-size: 300%;
}

@media screen and (min-width: 0) and (max-width: 1200px) {

.xe-widget { margin-bottom: 10px; position: relative; width: 100%; float: none;  margin-left: 0; }

.xe-widget.xe-counter, .xe-widget.xe-counter-block .xe-upper, .xe-widget.xe-progress-counter .xe-upper {
    padding: 0 10px !important;
}
}
</style>
        
     
        
	                    <div class="xe-widget xe-progress-counter xe-progress-counter-turquoise" data-count=".num" data-from="0" data-to="520" data-suffix="k" data-duration="3">
						
						<div class="xe-background">
							<i class="fa fa-street-view" aria-hidden="true"></i>

						</div>
						
						<div class="xe-upper">
							<div class="xe-icon">
								<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=0"><i class="fa fa-street-view" aria-hidden="true"></i></a>

							</div>
							<div class="xe-label">
								<span>LEADS </span>
								<strong class="num"><a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=0"><?php echo $bassa; ?></a></strong>
							</div>
						</div>
						
						<div class="xe-progress">
							<span style="width: 82%;" class="xe-progress-fill" data-fill-from="0" data-fill-to="82" data-fill-unit="%" data-fill-property="width" data-fill-duration="3" data-fill-easing="true"></span>
						</div>
						
						<div class="xe-lower">
							<span>BASSA PRIORIT&Agrave; (Cold)</span>
							<strong><?php echo numdec(($bassa/$totale)*100,2); ?>% DEL TOTALE</strong>
						</div>               
        			</div>  
                    
                      <div class="xe-widget xe-counter-block xe-counter-block-orange" data-count=".num" data-from="0" data-to="520" data-suffix="k" data-duration="3">
						
						<div class="xe-background">
							<i class="fa fa-street-view" aria-hidden="true"></i>

						</div>
						
						<div class="xe-upper">
							<div class="xe-icon">
								<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=1"><i class="fa fa-street-view" aria-hidden="true"></i></a>

							</div>
							<div class="xe-label">
								<span>LEADS</span>
								<strong class="num"><a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=1"><?php echo $media; ?></a></strong>
							</div>
						</div>
						
						<div class="xe-progress">
							<span style="width: 82%;" class="xe-progress-fill" data-fill-from="0" data-fill-to="82" data-fill-unit="%" data-fill-property="width" data-fill-duration="3" data-fill-easing="true"></span>
						</div>
						
						<div class="xe-lower">
							<span>MEDIA PRIORIT&Agrave; (Warm)</span>
							<strong><?php echo numdec(($media/$totale)*100,2); ?>% DEL TOTALE</strong>
						</div>               
        			</div>  
                    
                           <div class="xe-widget xe-counter-block xe-counter-block-red" data-count=".num" data-from="0" data-to="520" data-suffix="k" data-duration="3">
						
						<div class="xe-background">
							<i class="fa fa-street-view" aria-hidden="true"></i>


						</div>
						
						<div class="xe-upper">
							<div class="xe-icon">
								<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=2" ><i class="fa fa-street-view" aria-hidden="true"></i></a>


							</div>
							<div class="xe-label">
								<span>Leads</span>
								<strong class="num"><a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_leads/?priorita_contatto=2" ><?php echo $alta; ?></a></strong>
							</div>
						</div>
						
						<div class="xe-progress">
							<span style="width: 82%;" class="xe-progress-fill" data-fill-from="0" data-fill-to="82" data-fill-unit="%" data-fill-property="width" data-fill-duration="3" data-fill-easing="true"></span>
						</div>
						
						<div class="xe-lower">
							<span>ALTA PRIORIT&Agrave; (Hot)</span>
							<strong><?php echo numdec(($alta/$totale)*100,2); ?>% DEL TOTALE</strong>
						</div>               
        			</div>  


<br class="clear">