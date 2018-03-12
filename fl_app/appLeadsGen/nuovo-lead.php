
<div data-page="nuovo-lead" class="page">
  <div class="navbar">
    <div class="navbar-inner">
      <div class="left"><a href="forms.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
      <div class="center">Nuovo Lead</div>
      <div class="right"><a href="#" class="link open-panel icon-only"><i class="icon icon-bars"></i></a></div>
    </div>
  </div>

  <div class="page-content">
  
  <form id="form-lead">
    <div class="list-block inputs-list">
      <ul>
        <li>
          <div class="item-content">
            <div class="item-media"><i class="icon icon-form-name"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Nome</div>
              <div class="item-input">
                <input type="text" placeholder="" name="nome" value="" required />
              </div>
            </div>
          </div>
        </li>
        
         <li>
          <div class="item-content">
            <div class="item-media"><i class="icon icon-form-name"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Cognome</div>
              <div class="item-input">
                <input type="text" placeholder=""  name="cognome" value=""  required />
              </div>
            </div>
          </div>
        </li>
        
        
       

       <li>   <div class="item-content">
            <div class="item-media"><i class="icon icon-form-name"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Città</div>
              <div class="item-input">
                <input type="text" placeholder=""  name="citta" value=""  required />
              </div>
            </div>
          </div>
        </li>


   
        <li>
          <div class="item-content">
            <div class="item-media"><i class="icon icon-form-email"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">E-mail</div>
              <div class="item-input">
                <input type="email" placeholder="" name="email" />
              </div>
            </div>
          </div>
        </li>
        
                   <li>
          <div class="item-content">
            <div class="item-media"><i class="icon icon-form-tel"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Telefono</div>
              <div class="item-input">
                <input type="tel" placeholder="" name="telefono"  value=""  />
              </div>
            </div>
          </div>
        </li>
        
        
        

        <li>
          <div class="item-content">
                   <div class="content-block-title">Anno in cui sta programmando un evento?</div>

          </div>
         
          <ul>
          
              
      <?php for($i=2018;$i<2022;$i++) {
      
      echo '<li style="display: inline-block;">
          <label class="label-checkbox item-content">
            <input name="anno_di_interesse" value="'.$i.'" type="radio">
            <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
            <div class="item-inner">
              <div class="item-title">'.$i.'</div>
            </div>
          </label>
        </li>';

      } ?>


      </ul>
        </li>
        
    

                 


        <li>
          <div class="item-content">
            <div class="item-media"><i class="icon icon-form-toggle"></i></div>
            <div class="item-inner"> 
              <div class="item-title label">Desidera eprimere un interesse per un periodo legato ad un evento?</div>
              <div class="item-input">
                <label class="label-switch">
                  <input type="checkbox" value="1" name="interesse_tap" id="interesse_tap" />
                  <div class="checkbox"></div>
                </label>
              </div>
            </div>
          </div>
        </li>
        

<div id="interesse_box" >


   <li>
          <div class="item-content">
            <div class="item-media"></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Interessato a </div>
              <div class="item-input">
               <select class="" name="interessato_a" id="interessato_a">
<option value="-1">Seleziona...</option>
<option value="9">  Ricevimento con Rito Civile</option>
<option value="8">  Comunione</option>
<option value="7">  Party</option>
<option value="6">  Banchetto</option>
<option value="5">  Ricevimento</option>

</select>
              </div>
            </div>
          </div>
        </li>


        <li>
          <div class="item-content">
            <div class="item-media"></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Tipo di Evento</div>
              <div class="item-input">
               <select class="" name="tipo_interesse" id="tipo_interesse">
              <option value="-1">Seleziona...</option></select>        

              </div>
            </div>
          </div>
        </li>
      

     
</div>




          <li>   <div class="item-content">
            <div class="item-media"><i class="icon icon-form-name"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Numero di persone stimato</div>
              <div class="item-input">
                <input type="text" placeholder=""  name="numero_persone" value=""  />
              </div>
            </div>
          </div>
        </li>


 

  

        <li class="align-top">
          <div class="item-content">
            <div class="item-media"><i class="icon icon-form-comment"></i></div>
            <div class="item-inner"> 
              <div class="item-title floating-label">Note</div>
              <div class="item-input">
                <textarea class="resizable" name="note" ></textarea>
              </div>
            </div>
          </div>
        </li>
     
     
         
     
    <input type="hidden" value="1" name="insert_lead" />
    <input type="hidden" value="app" name="token" />
    
<input name="campagna" type="hidden" value="10"   />
<input name="attivita" type="hidden" value="16" />


      <li>
          <label class="label-checkbox item-content">
            <input name="privacy" value="1" type="checkbox" checked>
            <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
            <div class="item-inner">
              <div class="item-title-row">
                <div class="item-title">Inviando il form, autorizzo il trattamento dei dati personali in base all’Art.13 del D.L.vo 196/2003. </div>
                <div class="item-after"></div>
              </div>
              <div class="item-subtitle">INFORMATIVA E CONSENSO AI SENSI DEL D.LGS 196/2003</div>
              <div class="item-text" style="max-height: none;"> </div>
            </div>
          </label>
        </li>
        

        
         
        
      </ul>
    </div>

        <div class="content-block">
      <p class="buttons-row"><a href="#" class="button button-raised button-fill color-green">Invia</a></p>
</div>
</form>
</div></div>