<script>

  $(document).ready(function() {

    // Hard refresh when F5 is pressed
    document.onkeydown = fkey;
    document.onkeypress = fkey
    document.onkeyup = fkey;

    let wasPressed = false;

    function fkey(e){
      e = e || window.event;
      if( wasPressed ) return; 

        if (e.keyCode == 116) {
          location.reload(true);
          console.log("Hard Refreshed");
            wasPressed = true;
        }
    }

    const formEl = $(".ajaxForm");
    // Fetch all ingredients
    $.ajax({
      type: "GET",
      url: "load/get_ingredients.php",
      success: function result(response) {
        if (response != "0 resultati") {
          // Store ingredients in an array called allIngredients
          const allIngredients = JSON.parse(response);
          console.log("Ingredients: " + allIngredients.length);

          // Placeholder for submitting form
          /*formEl.submit(function(ev) {
            ev.preventDefault();
            alert("Clicked");
          });*/

          // Length of the table
          let datiTableLength = document.getElementById("dati").rows.length;

          // Add row
          $("#aggiungi").click(function(e,addRow) {
            e.preventDefault();

            $("#dati tbody").append(`
              <tr id="r${datiTableLength}">
                <td class="codice"><input class="codice-field" type="text" name="codice[]" placeholder="Inserisci codice">
                <input class="id-field" type="hidden" name="id[]"></td>
                <td class="descrizione"><input class="descrizione-field" type="text" name="descrizione[]" placeholder="Inserisci Descrizione"></td>
                <td class="um">
                  <select class="um-field select2" name="unita_di_misura[]" id="">
                    <option value="KG">KG</option>
                    <option value="LT">LT</option>
                    <option value="PZ">PZ</option>
                  </select>
                </td>
                <td class="qty"><input class="qty-field" type="number" step="1" min="0" name="qty[]" value="1"></td>
                <td class="prezzo"><input class="prezzo-field" step="0.01" min="0" type="number" name="prezzo[]" value="0.00"></td>
                <td class="sc"><input class="sc-field" type="text" name="sc[]" value="0"></td>
                <td class="iva"><input class="iva-field" type="text" name="iva[]" value="22"></td>
                <td class="importo"><input class="importo-field" step="0.01" min="0" type="number" name="importo[]" readonly></td>
                <th class="delete-row"></th>
              </tr>
            `);
            $(".dati-wrapper").animate(
              { scrollTop: $(".dati-wrapper").height() },
              "slow"
            );
            datiTableLength++;
            console.log(allIngredients);
          });

          // Delete row
          $(".dati-wrapper").click(function(e) {
            e.preventDefault();
            if (e.target.className == "delete-row") {
              $(`#${e.target.parentElement.id}`).remove();
            }
          });

          // Importo recalculate
          $(".dati-wrapper").change(function(e) {
            e.preventDefault();
            if (
              e.target.name == "qty[]" ||
              e.target.name == "prezzo[]" ||
              e.target.name == "sc[]" ||
              e.target.name == "iva[]"
            ) {
              const rowId = e.target.parentElement.parentElement.id;
              const qtyEl = $(`#${rowId} .qty-field`);
              const prezzoEl = $(`#${rowId} .prezzo-field`);
              const scEl = $(`#${rowId} .sc-field`);
              const ivaEl = $(`#${rowId} .iva-field`);
              const importoEl = $(`#${rowId} .importo-field`);

              if (qtyEl.val() != "" && prezzoEl.val() != "") {
                importoEl.val(qtyEl.val() * prezzoEl.val());
              }
            }
          });

          const results = {
            resultsHeader(rowId) {
              $(".results").remove();
              $(`#${rowId}`).after(
                '<div class="results"><ul id="results"></ul></div>'
              );
              $("#results").append(`
                        
                          <li class="row-result" id="results-header">
                            <h3 class="codice_descrizione">Codice</h3>
                            <h3 class="descrizione">Descrizione</h3>
                            
                            <h3 class="um">UM</h3>
                            
                            <h3 class="qty">QTY</h3>
                            <h3 class="prezzo">Prezzo</h3>
                            <h3 class="sc">Sc. %</h3>
                            <h3 class="iva">IVA</h3>
                            <h3 class="importo">Importo</h3>
                          </li>
                        
                        `);
            },
            loopThroughAllIngredients(codiceEl, descrizioneEl) {
              let inputFieldEl;
              let ingredientField;
              if (codiceEl != "") {
                inputFieldEl = codiceEl;
                ingredientField = "codice_fornitore[]";
              }
              if (descrizioneEl != "") {
                inputFieldEl = descrizioneEl;
                ingredientField = "formato[]";
              }
              $.each(allIngredients, function(index, ingredient) {
                if (ingredient[ingredientField].includes(inputFieldEl.val())) {
                  const prezzoUnitario = ingredient.prezzo_unitario;
                  const unita_di_misura_formato = ingredient.prezzo_unitario;
                  const importoResult = Number(
                    1 * Number(ingredient.prezzo_unitario)
                  ).toFixed(2);
                  $("#results").append(`
                  <li class="row-result" id="row-${index}" style="cursor:pointer;">
                      <p class="codice result-field">${
                        ingredient.codice_fornitore
                      }</p>
                      <p class="descrizione result-field">${
                        ingredient.formato
                      }</p>
                      
                      <p class="um result-field">${
                        ingredient.unita_di_misura_formato
                      }</p>
                      
                      <p class="qty result-field">1</p>
                      <p class="prezzo result-field">${
                        ingredient.prezzo_unitario
                      }</p>
                      <p class="sc result-field"></p>
                      <p class="iva result-field">${ingredient.iva}</p>
                      <p class="importo result-field">${Number(importoResult)}</p>
                    </li>
                  `);
                }
              });
            },
            closeResults() {
              window.onclick = function(e) {
                e.preventDefault();
                const codiceEl = $(`.codice-field`);
                const idEl = $(`.id-field`);
                const descrizioneEl = $(`.descrizione-field`);
                const qtyEl = $(`.qty-field`);
                const prezzoEl = $(`.prezzo-field`);
                const scEl = $(`.sc-field`);
                const ivaEl = $(`.iva-field`);
                const importoEl = $(`.importo-field`);

                if (
                  e.target.className != "codice-field" ||
                  e.target.className != "descrizione-field" ||
                  e.target.className != "qty-field" ||
                  e.target.className != "prezzo-field" ||
                  e.target.className != "sc-field" ||
                  e.target.className != "iva-field" ||
                  e.target.className != "importo-field"
                ) {
                  $(".results").remove();
                }
              };
            },
            fillInputs(rowId) {
              $("[id^=row-]").click(function(e) {
                let resultId = e.target.parentElement.id;
                const ingredientIndex = resultId.replace(/[^0-9]/g, "");
                const ingredientSelected = allIngredients[ingredientIndex];
                const codiceEl = $(`#${rowId} .codice-field`);
                const descrizioneEl = $(`#${rowId} .descrizione-field`);
                const qtyEl = $(`#${rowId} .qty-field`);
                const prezzoEl = $(`#${rowId} .prezzo-field`);
                const scEl = $(`#${rowId} .sc-field`);
                const ivaEl = $(`#${rowId} .iva-field`);
                const importoEl = $(`#${rowId} .importo-field`);
                const importoResult = (
                  1 * Number(ingredientSelected.prezzo_unitario)
                ).toFixed(2);

                codiceEl.val(ingredientSelected.codice_fornitore);
                descrizioneEl.val(ingredientSelected.formato);
                qtyEl.val(1);
                prezzoEl.val(
                  Number(ingredientSelected.prezzo_unitario).toFixed(2)
                );
                ivaEl.val(ingredientSelected.iva);
                $(
                  `#${rowId} option[value="${
                    ingredientSelected.unita_di_misura_formato
                  }"]`
                ).prop("selected", true);
                importoEl.val(Number(importoResult).toFixed(2));
                addRow();
                // ADD A ROW AND FOCUS ON LAST ROW CREATED CODICE
                e.preventDefault();
              });
              this.closeResults();
            },
            inputFieldConditionals(
              inputName,
              rowId,
              codiceEl = "",
              descrizioneEl = ""
            ) {
              if (inputName == "codice[]") {
                if (codiceEl.val() != "") {
                  if (codiceEl.val().length > 3) {
                    this.resultsHeader(rowId);
                    this.loopThroughAllIngredients(codiceEl, "");
                    this.fillInputs(rowId);
                  } else {
                    $("#results").remove();
                  }
                }
              }
              if (inputName == "descrizione[]") {
                if (descrizioneEl.val() != "") {
                  if (descrizioneEl.val().length > 3) {
                    this.resultsHeader(rowId);
                    this.loopThroughAllIngredients("", descrizioneEl);
                    this.fillInputs(rowId);
                  } else {
                    $("#results").remove();
                  }
                }
              }
            },
            showResults(rowId, inputName, e) {
              const codiceEl = $(`#${rowId} .codice-field`);
              const descrizioneEl = $(`#${rowId} .descrizione-field`);
              const qtyEl = $(`#${rowId} .qty-field`);
              const prezzoEl = $(`#${rowId} .prezzo-field`);
              const scEl = $(`#${rowId} .sc-field`);
              const ivaEl = $(`#${rowId} .iva-field`);
              const importoEl = $(`#${rowId} .importo-field`);

              this.inputFieldConditionals(
                inputName,
                rowId,
                codiceEl,
                descrizioneEl
              );
            }
          };

          // Results dropdown with codice field
          $(".dati-wrapper").keyup(function(e) {
            e.preventDefault();
            const rowId = e.target.parentElement.parentElement.id;
            const inputName = e.target.name;
            results.showResults(rowId, inputName, e);
          });
        } else {
          console.log(response);
        }
      },
      error: function(errorMessage) {
        console.error(errorMessage);
      }
    });

   
  });

</script>
