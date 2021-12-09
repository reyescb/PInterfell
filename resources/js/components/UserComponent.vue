<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Listado de Usuarios</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                Buscar:
                             </div>
                            <div class="col-md-2">
                                <input v-model="buscador" type="text" placeholder="Id" aria-label="Buscar" @keyup="showUserId" class="form-control buscador">
                             </div>
                             <div class="col-md-2">
                                <input v-model="fromDate" type="text" placeholder="Fecha Creación Inicio" aria-label="fechaInicio" @keyup.enter="fechaInicio" class="form-control fromDate">
                             </div>
                             <div class="col-md-2">
                                <input v-model="toDate" type="text" placeholder="Fecha Creación Fin" aria-label="fechaFin" @keyup.enter="fechaFin" class="form-control toDate">
                             </div>

                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table text-center"><!--Creamos una tabla que mostrará todas las tareas-->
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Apellido</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Url Imagen Perfil </th>
                                            <th scope="col">created_at </th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in arrayUsers" :key="user.id"> <!--Recorremos el array y cargamos nuestra tabla-->
                                            <td v-text="user.id"></td>
                                            <td v-text="user.first_name"></td>
                                            <td v-text="user.last_name"></td>
                                            <td v-text="user.description"></td>
                                            <td v-text="user.ima_profile"></td>
                                            <td v-text="user.created_at"></td>
                                            <td>
                                                <!--Botón modificar, que carga los datos del formulario con la tarea seleccionada-->
                                                <button class="btn" @click="loadFieldsUpdate(user)">Modificar</button>
                                                <!--Botón que borra la tarea que seleccionemos-->
                                                <button class="btn" @click="deleteUser(user)">Borrar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
                                    <label>Nombre</label>
                                    <input v-model="first_name" type="text" class="form-control">

                                    <label>Apellido</label>
                                    <input v-model="last_name" type="text" class="form-control">

                                    <label>Descripción</label>
                                    <input v-model="description" type="text" class="form-control">

                                    <label>Url Imagen Profile</label>
                                    <input v-model="ima_profile" type="text" class="form-control">

                                </div>
                                <div class="container-buttons">
                                    <hr class="dotted">
                                    <div class="form-group">
                                        <!-- Botón que añade los datos del formulario, solo se muestra si la variable update es igual a 0-->
                                        <button v-if="update == 0" @click="saveUsers()" class="btn btn-success">Añadir</button>
                                        <!-- Botón que modifica la tarea que anteriormente hemos seleccionado, solo se muestra si la variable update es diferente a 0-->
                                        <button v-if="update != 0" @click="updateUsers()" class="btn btn-warning">Actualizar</button>
                                        <!-- Botón que limpia el formulario y inicializa la variable a 0, solo se muestra si la variable update es diferente a 0-->
                                        <button v-if="update != 0" @click="clearFields()" class="btn">Atrás</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
         data(){
            return{
                first_name:"",
                last_name:"",
                description:"",
                ima_profile:"",
                update:"",
                buscador:"",
                fromDate:"",
                toDate:"",
                arrayUsers:[], //Este array contendrá las tareas de nuestra bd
            }
        },
        methods:{
            getUsers(){
                let me =this;
                let url = 'api/users' //Ruta que hemos creado para que nos devuelva todas las tareas
                axios.get(url).then(function (response) {

                    //creamos un array y guardamos el contenido que nos devuelve el response
                    me.arrayUsers = response.data.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            saveUsers(){
                let me =this;
                let url = 'api/users' //Ruta que hemos creado para enviar una tarea y guardarla
                axios.post(url,{ //estas variables son las que enviaremos para que crear la tarea
                    'first_name':this.first_name,
                    'last_name':this.last_name,
                    'description':this.description,
                    'ima_profile':this.ima_profile,
                }).then(function (response) {
                    me.getUsers();//llamamos al metodo getTask(); para que refresque nuestro array y muestro los nuevos datos
                    me.clearFields();//Limpiamos los campos e inicializamos la variable update a 0
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            updateUsers(){
                let me = this;
                axios.patch('api/users/'+this.update,{
                    'first_name':this.first_name,
                    'last_name':this.last_name,
                    'description':this.description,
                    'ima_profile':this.ima_profile,
                }).then(function (response) {
                   me.getUsers();//llamamos al metodo getTask(); para que refresque nuestro array y muestro los nuevos datos
                   me.clearFields();//Limpiamos los campos e inicializamos la variable update a 0
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            showUserId(){
                let me =this;
                let url = 'api/users/buscar/'+this.buscador;
                console.log(url);
                if(this.buscador === ''){
                    url = 'api/users';
                }
                axios.get(url).then(function (response) {
                    me.arrayUsers = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            loadFieldsUpdate(data){ //Esta función rellena los campos y la variable update, con la tarea que queremos modificar
                this.update = data.id
                let me =this;
                let url = 'api/users/'+this.update;
                axios.get(url).then(function (response) {
                    me.first_name= response.data.data.first_name;
                    me.last_name= response.data.data.last_name;
                    me.description= response.data.data.description;
                    me.ima_profile= response.data.data.ima_profile;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            deleteUser(data){//Esta nos abrirá un alert de javascript y si aceptamos borrará la tarea que hemos elegido
                let me =this;
                let user_id = data.id
                if (confirm('¿Seguro que deseas borrar esta tarea?')) {
                    axios.delete('api/users/'+user_id
                    ).then(function (response) {
                        me.getUsers();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            clearFields(){/*Limpia los campos e inicializa la variable update a 0*/
                this.first_name = "";
                this.last_name = "";
                this.description = "";
                this.ima_profile = "",
                this.update = 0;
                this.buscador = "";
                this.fromDate= "";
                this.toDate = "";
            },
            fechaInicio(){
                let me =this;
                let url = 'api/users/createdAt';
                if(this.fromDate !== ''){
                     axios.post(url,{
                    'fromDate':me.fromDate
                    }).then(function (response) {
                        console.log(response);
                    me.arrayUsers = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }else{
                     let url = 'api/users';
                    axios.get(url).then(function (response) {
                    me.arrayUsers = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            }
            ,
            fechaFin(){
                let me =this;
                let url = 'api/users/betweenCreatedAt';
                if(me.fromDate === ''){
                    alert("Fecha de Inicio Vacia");
                    return;
                }
                if(this.fromDate !== ''){
                     axios.post(url,{
                    'fromDate':me.fromDate,
                    'toDate':me.toDate
                    }).then(function (response) {
                        console.log(response);
                    me.arrayUsers = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }else{
                     let url = 'api/users';
                    axios.get(url).then(function (response) {
                    me.arrayUsers = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            }
        },
        mounted() {

            this.getUsers();
            this.update = 0;
        }
    }

</script>
