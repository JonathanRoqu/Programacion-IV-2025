import { createApp } from 'vue';
import db from './db.js';
import autor from './autor.js';
import libros from './libros.js';

const app = createApp({
    components: {
        autor,
        libros
    },
    data() {
        return {
            forms: {
                autor: { mostrar: false },
                libros: { mostrar: false }
            }
        };
    },
    methods: {
        cerrarFormularios() {
            Object.keys(this.forms).forEach(key => {
                this.forms[key].mostrar = false;
            });
        },
        abrirFormulario(componente) {
            this.cerrarFormularios();
            this.forms[componente].mostrar = true;
        }
    }
});

app.mount('#app');