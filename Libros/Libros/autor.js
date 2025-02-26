import db from './db.js';

export default {
    template: `
        <div class="container mt-5">
            <h2 class="text-center mb-4">Formulario de Autores</h2>
            <div class="card shadow">
                <div class="card-body">
                    <form @submit.prevent="addAutor" class="mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" v-model="autorForm.codigo" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" v-model="autorForm.nombre" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pais">País</label>
                                <input type="text" class="form-control" v-model="autorForm.pais" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" v-model="autorForm.telefono" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Agregar Autor</button>
                    </form>

                    <h3 class="text-center mb-3">Buscar Autores</h3>
                    <form @submit.prevent="searchAutores" class="mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="searchCodigo">Código</label>
                                <input type="text" class="form-control" v-model="searchAutor.codigo">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="searchNombre">Nombre</label>
                                <input type="text" class="form-control" v-model="searchAutor.nombre">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="searchPais">País</label>
                                <input type="text" class="form-control" v-model="searchAutor.pais">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-block">Buscar</button>
                    </form>

                    <h3 class="text-center mb-3">Lista de Autores</h3>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>País</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="autor in autores" :key="autor.idAutor">
                                <td>{{ autor.codigo }}</td>
                                <td>{{ autor.nombre }}</td>
                                <td>{{ autor.pais }}</td>
                                <td>{{ autor.telefono }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" @click="updateAutor(autor.idAutor)">Modificar</button>
                                    <button class="btn btn-danger btn-sm" @click="deleteAutor(autor.idAutor)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `,
    data() {
        return {
            autores: [],
            autorForm: {
                codigo: '',
                nombre: '',
                pais: '',
                telefono: ''
            },
            searchAutor: {
                codigo: '',
                nombre: '',
                pais: ''
            }
        };
    },
    methods: {
        async addAutor() {
            await db.autor.add(this.autorForm);
            this.autorForm = { codigo: '', nombre: '', pais: '', telefono: '' };
            this.loadAutores();
        },
        async updateAutor(idAutor) {
            await db.autor.put({ ...this.autorForm, idAutor });
            this.loadAutores();
        },
        async deleteAutor(idAutor) {
            await db.autor.delete(idAutor);
            this.loadAutores();
        },
        async searchAutores() {
            this.autores = await db.autor.where('codigo').startsWith(this.searchAutor.codigo)
                .and(autor => autor.nombre.includes(this.searchAutor.nombre))
                .and(autor => autor.pais.includes(this.searchAutor.pais))
                .toArray();
        },
        async loadAutores() {
            this.autores = await db.autor.toArray();
        }
    },
    mounted() {
        this.loadAutores();
    }
};