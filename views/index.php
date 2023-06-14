<!doctype html>
<html lang="en">

<head>
  <title>Filtro 1</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>

  <div class="container">
    
    <!-- Cabecera -->
    <div class="row text-center mt-3">
      <h3>Super héroes - Filtro 1</h3>
      <p>Reportes en formato PDF</p>
    </div>

    <!-- Filtro -->
    <div class="row">
      <div class="col-md-12">
        <!-- Inicio de card -->
        <div class="card">
          <!-- Filtro de casas y bando -->
          <div class="card-header">
            Filtro de casas y bando
          </div>
          <div class="card-body">

            <div class="row">

              <!-- Primer filtro de casas -->
              <div class="col-md-5">
                <label for="">Casa distribuidora</label>
                <select name="" id="casas" class="form-select">
                  <option value="0">Seleccione</option>
                </select>
              </div>

              <!-- Segundo filtro de bandos -->
              <div class="col-md-5">
                <label for="">Bando</label>
                <select name="" id="bando" class="form-select">
                  <option value="0">Seleccione</option>
                </select>
              </div>

              <div class="col-md-2 mt-4">
                <div class="d-grid">
                  <button type="button" id="generarPDF" class="btn btn-outline-success">Generar PDF</button>
                </div>
              </div>

            </div>

          </div><!-- Fin card-body -->
        </div><!-- Fin de card -->
      </div>
    </div>

    <!-- Datos - tabla -->

    <div class="row mt-2">

      <div class="col-md-12">
        <table class="table display responsive nowrap table-striped" id="table-superhero">
          <colgroup>
            <col width="5%">
            <col width="20%">
            <col width="35%">
            <col width="20%">
            <col width="20%">
          </colgroup>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nick</th>
              <th>Hombre</th>
              <th>Raza</th>
              <th>Casa Publicadora</th>
            </tr>
          </thead>
          <tbody>

          </tbody>

        </table>
      </div>

    </div>

  </div>


  <script>
    // Evento que se dispara cuando el DOM está completamente cargado
    document.addEventListener("DOMContentLoaded", () => {
      
      const selectCasas = document.querySelector("#casas"); // Es utilizado para acceder al elemento <select> que representa las cadas en el formulario.
      const selectBando = document.querySelector("#bando"); // Es utilizado para acceder al elemento <select> que representa los bandos en el formulario.
      const tabla = document.querySelector("#table-superhero tbody") // La variable tabla se utiliza para acceder a este elemento y modificar su contenido.
      const btnGenerar = document.querySelector("#generarPDF"); // Es utilizado para acceder al elemento <button> y despues darle funcionalidad
      let filtroPDF = -1; // Creo una variable para un filtro del GenerarPDF

      // Función para obtener las casas distribuidoras desde el servidor
      function getPublishers() {

        //Parametros que le mandaremos al controlador
        const parametros = new URLSearchParams();
        parametros.append('operacion', 'listAll');

        fetch(`../controllers/publisher.php?${parametros}`)
          .then(respuesta => respuesta.json())
          .then(datos => {


            // Comenzamos a iterar sobre un arreglo llamado datos 
            // que viene de la respuesta del servidor y 
            // realizamos las siguientes acciones para cada elemento del arreglo:
            
            datos.forEach(element => {
              
              // Crea un nuevo elemento option utilizando document.createElement("option").
              const optionTag = document.createElement("option");
              
              // Asigna el valor element.id al atributo value del elemento option.
              optionTag.value = element.id;
              
              // Asigna el valor element.publisher_name al contenido de texto del elemento option.
              optionTag.text = element.publisher_name;

              // Agrega el elemento option como hijo del elemento selectCasas.
              selectCasas.appendChild(optionTag);
            })

            // En caso de ocurrir un error este se muestra en consola
          }).catch(error => {
            console.log(error)
          })

      }

      // Función para obtener los bandos desde el servidor
      function getAlignment() {

        //Parametros que le mandaremos al controlador
        const parametros = new URLSearchParams();
        parametros.append('operacion', 'listAll');

        fetch(`../controllers/alignment.php?${parametros}`)
          .then(respuesta => respuesta.json())
          .then(datos => {

            // Comenzamos a iterar sobre un arreglo llamado datos 
            // que viene de la respuesta del servidor y 
            // realizamos las siguientes acciones para cada elemento del arreglo:

            datos.forEach(element => {

              // Crea un nuevo elemento option utilizando document.createElement("option").
              const optionTag = document.createElement("option");

              // Asigna el valor element.id al atributo value del elemento option.
              optionTag.value = element.id;

              // Asigna el valor element.alignment al contenido de texto del elemento option.
              optionTag.text = element.alignment;

              // Agrega el elemento option como hijo del elemento selectBando.
              selectBando.appendChild(optionTag);
            })

            // En caso de ocurrir un error este se muestra en consola
          }).catch(error => {
            console.log(error)
          })

      }

      // Función para renderizar los datos según el filtro seleccionado
      function render() {

        /* 
        Toma el valor seleccionado del elemento selectCasas 
        y lo convierte a un número entero utilizando la función parseInt()
        */
        const optionCasa = parseInt(selectCasas.value);

        /* 
        Toma el valor seleccionado del elemento selectBando 
        y lo convierte a un número entero utilizando la función parseInt()
        */
        const optionBando = parseInt(selectBando.value);

        /*
        Se utiliza un operador tenario el cual dice que 
        si la opcion del bando es igual o mayor a 1 este
        mantiene su valor del option de no ser asi el valor
        sera 0 
        */

        /*
        Esto lo hago por que en el procedimiento almacenado que tengo
        tiene una condicional la cual es si le paso el numero 0 me filtra todo
        */

        const operador = optionBando >= 1 ? optionBando : "0";


        //Parametros que le mandaremos al controlador
        const parametros = new URLSearchParams();
        parametros.append('operacion', 'listByPublisherAndAlignment');
        parametros.append('publisher_id', optionCasa);
        parametros.append('alignment_id', operador);

        fetch(`../controllers/superhero.php?${parametros}`)
          .then(respuesta => respuesta.text()) //Mandamos la respuesta del servidor con formato text
          .then(datos => {

            /*
              Hago una condicional donde si no existen datos del
              servidor o la longitud de los datos es igual 0
              esta me retorna un alert, reinicia la tabla,
              el select lo hace volver a elegir y
              la variable del filtro de pdf la mantengo en -1
            */
            if (!datos || datos.length === 0) {
              alert('No hay datos disponibles.');
              tabla.innerHTML = "";
              selectBando.value = 0
              filtroPDF = -1;
            } else { // de pasar este filtro de arriba con un else ejecuto lo siguiente:

              registro = JSON.parse(datos) //Convierto los datos recibidos en un JSON

              tabla.innerHTML = ""; //Reinicio la tabla antes de poblarla

              filtroPDF = 1 // De haber pasado el filtro anterior la variable filtroPDF tendra un valor de 1


              /*
               Generamos filas de tabla HTML en base a los elementos del arreglo registro 
               y las agregamos dinámicamente al contenido de la tabla identificada por 
               el elemento tabla. Cada fila de la tabla contiene datos específicos 
               del objeto element para las columnas 
               de ID, nombre de superhéroe, nombre completo, raza y publicador.
              */

              registro.forEach(element => {

                let tableRow =
                  `
                <tr>
                  <td>${element['id']}</td>
                  <td>${element['superhero_name']}</td>
                  <td>${element['full_name']}</td>
                  <td>${element['race']}</td>
                  <td>${element['publisher']}</td>
                </tr>            
                `
                tabla.innerHTML += tableRow;

              })
            }
          })
      }

      // Función para generar el PDF según el filtro seleccionado
      function PDFBuild() {

        /*
        Hacemos una condicional donde si el option del selectBando
        tiene un valor de 0 este le indique que tiene que elegir
        uno para asi poder crear el pdf
        */

        if (selectBando.value == 0) {

          alert("Debes elegir un bando para poder crear el PDF");
          
        } 
        

        /*
        De haber pasado la primera condicional hacemos otra donde
        si la variable filtroPDF tiene un valor mayor a 0 esta
        genere el PDF
        */

        else if (filtroPDF > 0) {

          //Mandamos los parametros a la url que nos genera los PDF
          const parametros = new URLSearchParams();
          parametros.append("publisher_id", selectCasas.value)
          parametros.append("alignment_id", selectBando.value)
          parametros.append("titulo", selectCasas.options[selectCasas.selectedIndex].text)

          //Abrimos en una ventana aparte el PDF generado
          window.open(`../reports/filtro1/reporte.php?${parametros}`, '_blank')

        } 
        
        /* 
        De no haber datos en la tabla que vienen del servidor
        segun los filtros elegidos esta mostrara un alert informandolo
        */

        else {
          alert("No existen datos disponibles para generar el PDF");
        }

      }

      // Evento para renderizar los datos al cambiar el valor del select de casas
      selectCasas.addEventListener('change', render);

      // Evento para renderizar los datos al cambiar el valor del select de bandos
      selectBando.addEventListener('change', render);

      // Evento para generar el PDF al hacer clic en el botón
      btnGenerar.addEventListener('click', PDFBuild)

      // Obtener las casas distribuidoras y los bandos
      getPublishers();
      getAlignment();

    })
  </script>

</body>

</html>
