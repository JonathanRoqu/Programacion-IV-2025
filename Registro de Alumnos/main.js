const { createApp } = Vue;

createApp({
    data() {
        return {
            mostrarPantallaInicial: true,
            alumnos: [],
            alumnosFiltrados: [],
            busqueda: '',
            codigo: '',
            nombre: '',
            direccion: '',
            municipio: '',
            distrito: '',
            telefono: '',
            fechaNacimiento: '',
            sexo: '',
            reproduciendo: false,
            buscadorAbierto: false,
            municipios: {
                "Ahuachapán Norte": ["Atiquizaya", "El Refugio", "San Lorenzo", "Turín"],
                "Ahuachapán Centro": ["Ahuachapán", "Apaneca", "Concepción de Ataco", "Tacuba"],
                "Ahuachapán Sur": ["Guaymango", "Jujutla", "San Francisco Menendez", "San Pedro Puxtla"],
                "San Salvador Norte": ["Aguilares", "El Paisnal", "Guazapa"],
                "San Salvador Oeste": ["Apopa", "Nejapa"],
                "San Salvador Este": ["llopango", "San Martín", "Soyapango", "Tonacatepeque"],
                "San Salvador Centro": ["Ayutuxtepeque", "Mejicanos", "San Salvador", "Cuscatancingo", "Ciudad Delgado"],
                "San Salvador Sur": ["Panchimalco", "Rosario de Mora", "San Marcos", "Santo Tomás", "Santiago Texacuangos"],
                "La Libertad Norte": ["Quezaltepeque", "San Matías", "San Pablo Tacachico"],
                "La Libertad Centro": ["San Juan Opico", "Ciudad Arce"],
                "La Libertad Oeste": ["Colón", "Jayaque", "Sacacoyo", "Tepecoyo", "Talnique"],
                "La Libertad Este": ["Antiguo Cuscatlán", "Huizucar", "Nuevo Cuscatlán", "San José Villanueva", "Zaragoza"],
                "La Libertad Costa": ["Chiltuipán", "Jicalapa", "La Libertad", "Tamanique", "Teotepeque"],
                "La Libertad Sur": ["Comasagua", "Santa Tecla"],
                "Chalatenango Norte": ["La Palma", "Citalá", "San Ignacio"],
                "Chalatenango Centro": ["Nueva Concepción", "Tejutla", "La Reina", "Agua Caliente", "Dulce Nombre de María", "El Paraíso", "San Francisco Morazán", "San Rafael", "Santa Rita", "San Fernando"],
                "Chalatenango Sur": ["Chalatenango", "Arcatao", "Azacualpa", "Comalapa", "Concepción Quezaltepeque", "El Carrizal", "La Laguna", "Las Vueltas", "Nombre de Jesús", "Nueva Trinidad", "Ojos de Agua", "Potonico", "San Antonio de La Cruz", "San Antonio Los Ranchos", "San Francisco Lempa", "San Isidro Labrador", "San José Cancasque", "San Miguel de Mercedes", "San José Las Flores", "San Luis del Carmen"],
                "Cuscatlán Norte": ["Suchitoto", "San José Guayabal", "Oratorio de Concepción", "San Bartolomé Perulapán", "San Pedro Perulapán"],
                "Cuscatlán Sur": ["Cojutepeque", "San Rafael Cedros", "Candelaria", "Monte San Juan", "El Carmen", "San Cristóbal", "Santa Cruz Michapa", "San Ramón", "El Rosario", "Santa Cruz Analquito", "Tenancingo"],
                "Cabañas Este": ["Sensuntepeque", "Victoria", "Dolores", "Guacotecti", "San Isidro"],
                "Cabañas Oeste": ["llobasco", "Tejutepeque", "Jutiapa", "Cinquera"],
                "La Paz Oeste": ["Cuyultitán", "Olocuilta", "San Juan Talpa", "San Luis Talpa", "San Pedro Masahuat", "Tapalhuaca", "San Francisco Chinameca"],
                "La Paz Centro": ["El Rosario", "Jerusalén", "Mercedes La Ceiba", "Paraíso de Osorio", "San Antonio Masahuat", "San Emigdio", "San Juan Tepezontes", "San Luis La Herradura", "San Miguel Tepezontes", "San Pedro Nonualco", "Santa María Ostuma", "Santiago Nonualco"],
                "La Paz Este": ["San Juan Nonualco", "San Rafael Obrajuelo", "Zacatecoluca"],
                "La Unión Norte": ["Anamorós", "Bolivar", "Concepción de Oriente", "El Sauce", "Lislique", "Nueva Esparta", "Pasaquina", "Polorós", "San José La Fuente", "Santa Rosa de Lima"],
                "La Unión Sur": ["Conchagua", "El Carmen", "lntipucá", "La Unión", "Meanguera del Golfo", "San Alejo", "Yayantique", "Yucuaiquín"],
                "Usulután Norte": ["Santiago de María", "Alegría", "Berlín", "Mercedes Umana", "Jucuapa", "El Triunfo", "Estanzuelas", "San Buenaventura", "Nueva Granada"],
                "Usulután Este": ["Usulután", "Jucuarán", "San Dionisio", "Concepción Batres", "Santa María", "Ozatlán", "Tecapán", "Santa Elena", "California", "Ereguayquín"],
                "Usulután Oeste": ["Jiquilisco", "Puerto El Triunfo", "San Agustín", "San Francisco Javier"],
                "Sonsonate Norte": ["Juayúa", "Nahuizalco", "Salcoatitán", "Santa Catarina Masahuat"],
                "Sonsonate Centro": ["Sonsonate", "Sonzacate", "Nahulingo", "San Antonio del Monte", "Santo Domingo de Guzmán"],
                "Sonsonate Este": ["lzalco", "Armenia", "Caluco", "San Julián", "Cuisnahuat", "Santa Isabel lshuatán"],
                "Sonsonate Oeste": ["Acajutla"],
                "Santa Ana Norte": ["Masahuat", "Metapán", "Santa Rosa Guachipilín", "Texistepeque"],
                "Santa Ana Centro": ["Santa Ana"],
                "Santa Ana Este": ["Coatepeque", "El Congo"],
                "Santa Ana Oeste": ["Candelaria de la Frontera", "Chalchuapa"]
            },
            distritosFiltrados: []
        };
    },
    methods: {
        cargarDistritos() {
            this.distritosFiltrados = this.municipios[this.municipio] || [];
            this.distrito = ''; // Resetear el distrito al cambiar de municipio
        },
        guardarAlumno() {
            let alumnoExistente = localStorage.getItem(this.codigo);
            let alumno = {
                codigo: this.codigo,
                nombre: this.nombre,
                direccion: this.direccion,
                municipio: this.municipio,
                distrito: this.distrito,
                telefono: this.telefono,
                fechaNacimiento: this.fechaNacimiento,
                sexo: this.sexo
            };

            if (alumnoExistente) {
                let alumnoGuardado = JSON.parse(alumnoExistente);
                if (
                    alumnoGuardado.nombre !== this.nombre ||
                    alumnoGuardado.direccion !== this.direccion ||
                    alumnoGuardado.municipio !== this.municipio ||
                    alumnoGuardado.distrito !== this.distrito ||
                    alumnoGuardado.telefono !== this.telefono ||
                    alumnoGuardado.fechaNacimiento !== this.fechaNacimiento ||
                    alumnoGuardado.sexo !== this.sexo
                ) {
                    localStorage.setItem(this.codigo, JSON.stringify(alumno));
                    this.listarAlumnos();
                    this.limpiarFormulario();
                    alert("Datos actualizados correctamente.");
                } else {
                    alert("No se realizaron cambios. Los datos no se actualizaron.");
                }
            } else {
                localStorage.setItem(this.codigo, JSON.stringify(alumno));
                this.listarAlumnos();
                this.limpiarFormulario();
                alert("Alumno registrado correctamente.");
            }
        },
        listarAlumnos() {
            this.alumnos = [];
            for (let i = 0; i < localStorage.length; i++) {
                let clave = localStorage.key(i),
                    valor = localStorage.getItem(clave);
                try {
                    let alumno = JSON.parse(valor);
                    this.alumnos.push(alumno);
                } catch (e) {
                    console.error("Error al parsear los datos de alumno", e);
                }
            }
            this.filtrarAlumnos();
        },
        verAlumno(alumno) {
            this.codigo = alumno.codigo;
            this.nombre = alumno.nombre;
            this.direccion = alumno.direccion;
            this.municipio = alumno.municipio;
            this.distrito = alumno.distrito;
            this.telefono = alumno.telefono;
            this.fechaNacimiento = alumno.fechaNacimiento;
            this.sexo = alumno.sexo;
        },
        eliminarAlumno(alumno) {
            if (confirm(`¿Está seguro de eliminar al alumno ${alumno.nombre}?`)) {
                localStorage.removeItem(alumno.codigo);
                this.listarAlumnos();
            }
        },
        reproducirMusica() {
            const audio = this.$refs.audio;
            const botonMusica = this.$refs.botonMusica;

            if (this.reproduciendo) {
                audio.pause();
                this.reproduciendo = false;
                botonMusica.classList.remove("girando", "reproduciendo");
            } else {
                audio.play();
                this.reproduciendo = true;
                botonMusica.classList.add("girando", "reproduciendo");
            }
        },
        limpiarFormulario() {
            this.codigo = '';
            this.nombre = '';
            this.direccion = '';
            this.municipio = '';
            this.distrito = '';
            this.telefono = '';
            this.fechaNacimiento = '';
            this.sexo = '';
        },
        filtrarAlumnos() {
            const termino = this.busqueda.toLowerCase();
            this.alumnosFiltrados = this.alumnos.filter(alumno => {
                return (
                    alumno.codigo.toLowerCase().includes(termino) ||
                    alumno.nombre.toLowerCase().includes(termino) ||
                    alumno.distrito.toLowerCase().includes(termino)
                );
            });
        },
        toggleBuscador() {
            this.buscadorAbierto = !this.buscadorAbierto;
        }
    },
    watch: {
        busqueda() {
            this.filtrarAlumnos();
        }
    },
    mounted() {
        this.listarAlumnos();
    }
}).mount('#app');