import db from './db.js';

export default {
    template: `
        <div class="container mt-5">
            <h2 class="text-center mb-4">Formulario de Libros</h2>
            <div class="card shadow">
                <div class="card-body">
                    <form @submit.prevent="addLibro" class="mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="idAutor">ID Autor</label>
                                <input type="text" class="form-control" v-model="libroForm.idAutor" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Isbn">ISBN</label>
                                <input type="text" class="form-control" v-model="libroForm.Isbn" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="titulo">Título</label>
                                <input type="text" class="form-control" v-model="libroForm.titulo" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editorial">Editorial</label>
                                <input type="text" class="form-control" v-model="libroForm.editorial" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="edicion">Edición</label>
                                <input type="text" class="form-control" v-model="libroForm.edicion" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Agregar Libro</button>
                    </form>

                    <h3 class="text-center mb-3">Buscar Libros</h3>
                    <form @submit.prevent="searchLibros" class="mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="searchTitulo">Título</label>
                                <input type="text" class="form-control" v-model="searchLibro.titulo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="searchEditorial">Editorial</label>
                                <input type="text" class="form-control" v-model="searchLibro.editorial">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-block">Buscar</button>
                    </form>

                    <h3 class="text-center mb-3">Lista de Libros</h3>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Autor</th>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Editorial</th>
                                <th>Edición</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="libro in libros" :key="libro.idLibro">
                                <td>{{ libro.idAutor }}</td>
                                <td>{{ libro.Isbn }}</td>
                                <td>{{ libro.titulo }}</td>
                                <td>{{ libro.editorial }}</td>
                                <td>{{ libro.edicion }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" @click="updateLibro(libro.idLibro)">Modificar</button>
                                    <button class="btn btn-danger btn-sm" @click="deleteLibro(libro.idLibro)">Eliminar</button>
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
            libros: [],
            libroForm: {
                idAutor: '',
                Isbn: '',
                titulo: '',
                editorial: '',
                edicion: ''
            },
            searchLibro: {
                titulo: '',
                editorial: ''
            }
        };
    },
    methods: {
        async addLibro() {
            await db.libros.add(this.libroForm);
            this.libroForm = { idAutor: '', Isbn: '', titulo: '', editorial: '', edicion: '' };
            this.loadLibros();
        },
        async updateLibro(idLibro) {
            await db.libros.put({ ...this.libroForm, idLibro });
            this.loadLibros();
        },
        async deleteLibro(idLibro) {
            await db.libros.delete(idLibro);
            this.loadLibros();
        },
        async searchLibros() {
            this.libros = await db.libros.where('titulo').startsWith(this.searchLibro.titulo)
                .and(libro => libro.editorial.includes(this.searchLibro.editorial))
                .toArray();
        },
        async loadLibros() {
            this.libros = await db.libros.toArray();
        }
    },
    mounted() {
        this.loadLibros();
    }
};