<style>
.container {
  height: 75vh;
}

.ajaxForm {
  height: 100%;
}

.dati {
}

.dati-wrapper {
  height: auto;
  max-height: 90%;
  overflow-y: auto;
}

.delete-row:before {
  font-size: 1.5em;
  font-family: FontAwesome;
  content: "\f014";
}

#aggiungi {

  text-align: right;

}

/* input[type="submit"] {
  margin-left: 1.9em;
  margin-bottom: 2em;
} */

/* MODAL */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0, 0, 0);
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  display: flex;
  flex-direction: column;
  background-color: white;
  margin: 5% auto;
  padding: 1.5em;
  border: none;
  width: 90%;
}

.results {
  background-color: #fff;
  position: absolute;
  width: 95%;
  box-shadow: 10px 10px 5px grey;
  padding-left: 0;
}

.row-result {
  padding: 1em;
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-auto-flow: column;
  text-align: left;
  width: 100%;
  margin: 0;
}

#results-header > li > h3,
#results-header {
  margin: 0;
}

.row-result:hover:not(:first-child) {
  background-color: wheat;
}

.selected {
  background-color: wheat;
}

.row-result:first-child {
  background-color: rgba(187, 184, 184, 0.952);
}

.result-field {
  margin-top: 0;
  margin-bottom: 0;
  height: 100%;
  width: 100%;
}

#results {
  margin: 0;
  width: 100%;
  padding: 0;
}

#results > li {
  list-style-type: none;
}

.um {
  width: 5em;
}
</style>