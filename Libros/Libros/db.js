const db = new Dexie("db_codigo_estudiante");
db.version(1).stores({
    autor: "++idAutor, codigo, nombre, pais, telefono",
    libros: "++idLibro, idAutor, Isbn, titulo, editorial, edicion"
});

export default db;