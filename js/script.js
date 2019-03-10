(function () {

    /* global $ */

    var aliasok = true;
    var emailok = false;
    var usuario = null;

    var main = function() {
        getEnlaces();
        getCategoriasSelect();
    };

    var genericAjax = function(url, data, type, callBack) {
        $.ajax({
                url: url,
                data: data,
                type: type,
                dataType: 'json',
            })
            .done(function(json) {
                console.log('ajax done');
                console.log(json);
                callBack(json);
            })
            .fail(function(xhr, status, errorThrown) {
                console.log(xhr);
                console.log(status);
                console.log(errorThrown);
                console.log('ajax fail');
            })
            .always(function(xhr, status) {
                console.log('ajax always');
            });
    }
    
    var genericUploadAjax = function (url, idFile, callBack) {
        var formData = new FormData();
        var file = document.getElementById(idFile);
        var file = $('#' + idFile)[0];
        formData.append('image', file.files[0]);
        formData.append('correo', usuario.correo);
        $.ajax({
            url: url,
            data: formData,
            type: 'POST',
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT THIS
            dataType : 'json',
        })
        .done(function( json ) {
            console.log('ajax done');
            console.log(json);
            callBack(json);
        })
        .fail(function( xhr, status, errorThrown ) {
            console.log('ajax fail');
        })
        .always(function( xhr, status ) {
            console.log('ajax always');
        });
    }

    var getEnlaces = function (pagina, orden) {
        genericAjax('ajax/listalinks', {'pagina': pagina, 'orden': orden}, 'get', function(json) {
            procesarEnlaces(json.enlaces);
            procesarPaginas(json.paginas);
        });
    }
    
    var getEnlacesPorCategoria = function (pagina, categoria) {
        genericAjax('ajax/listalinksporcategoria', {'pagina': pagina, 'categoria': categoria}, 'get', function(json) {
            procesarEnlaces(json.enlaces);
            procesarPaginas(json.paginas);
        });
    }
    
    var getCategoriasSelect = function () {
        genericAjax('ajax/listacategoria', null, 'get', function(json) {
            procesarCategoriasSelect(json.categoria);
            
        });
    }

    var getTrEnlaces = function (value) {
        return `<tr>
                    <td>${value.id}</td>
                    <td>${value.categoria.nombre}</td>
                    <td><a href="${value.href}">${value.href}</a></td>
                    <td>${value.descripcion}</td>
                    <td><input class="eliminaenlace btn btn-primary mb-3" data-idenlace="${value.id}" type="button" value="Eliminar"></td>
                </tr>`;
    };
    
    var getOptionCategoria = function (value) {
        return `<option value="${value.id}">${value.nombre}</option>`;
    };
    var getLiCategoria = function (value) {
        return `<li class="btn btn-warning" style="width:100%"><a data-category="${value.id}" href"">${value.nombre}</a></li><br/>`;
    };
    var procesarEnlaces = function (links) {
        var listaitems = '';
        $.each(links, function(key, value) {
            listaitems += getTrEnlaces(value);
        });
        $('#cuerpoTablaCiudades').empty();
        $('#cuerpoTablaCiudades').append(listaitems);
        
        $('.eliminaenlace').on('click', function(e) {
            if (confirm('¿Seguro que quieres borrar?')) {
                var id = $(this).data('idenlace');
                genericAjax('ajax/eliminaenlace', { 'id': id }, 'post', main);
            }
        })
    }
    
    var procesarCategoriasSelect = function (categorias) {
        var listaitems = '';
        $.each(categorias, function(key, value) {
            listaitems += getOptionCategoria(value);
        });
        $('#categoriaenlace').empty();
        $('#categoriaenlace').append(listaitems);
        
        var listaitems2 = '';
        $.each(categorias, function(key, value) {
            listaitems2 += getLiCategoria(value);
        });
        $('#ulcategorias').empty();
        $('#ulcategorias').append(listaitems2);
        $('li a').on('click', function(e) {
            e.preventDefault();
            var categoria = e.target.getAttribute('data-category');
            console.log(categoria);
            getEnlacesPorCategoria(null, categoria);
        });
    }
    
    var procesarPaginas = function (paginas) {
        var stringFirst = '<a href = "#" class = "btn btn-primary">' + paginas.primero + '</a>';
        var stringPrev  = '<a href = "#" class = "btn btn-primary">' + paginas.anterior + '</a>';
        var stringRange = '';
        $.each(paginas.rango, function(key, value) {
            if(paginas.pagina == value) {
                stringRange += '<a href = "#" class = "btnNoPagina btn btn-info">' + value + '</a> ';
            } else {
                stringRange += '<a href = "#" class = "btnPagina btn btn-primary" data-pagina="' + value + '">' + value + '</a> ';
            }
        });
        var stringNext = '<a href = "#" class = "btnPagina btn btn-primary">' + paginas.siguiente + '</a>';
        var stringLast = '<a href = "#" class = "btnPagina btn btn-primary">' + paginas.ultimo + '</a>';
        var finalString = stringFirst + stringPrev + stringRange + stringNext + stringLast;
        $('#pintarPaginas').empty();
        $('#pintarPaginas').append(stringRange);
        $('.btnPagina').on('click', function(e) {
            e.preventDefault();
            var p = e.target.getAttribute('data-pagina');
            getEnlaces(p, null);
        });
        $('.btnNoPagina').on('click', function(e) {
            e.preventDefault();
        });
    }

    $(document).ajaxStart(function () {
        console.log('pre shadow');
        $('#loading').show();
    });

    $(document).ajaxStop(function () {
        console.log('post shadow');
        $('#loading').hide();
    });

    $('#avatar').change(function (evt) {
        genericUploadAjax('ajax/upload', 'avatar', function(json) {
            if(json.upload) {
                var target = document.getElementById("imagenphp");
                target.src = json.file;
            }
        });
    });
    
    $('#btAvatar').on('click', function(event) {
        $('#avatar').trigger('click');
    });
    
    $('#ordenaporid').on('click', function(e) {
        e.preventDefault();
        getEnlaces(null, 'id');
    });
    $('#ordenaporcategoria').on('click', function(e) {
        e.preventDefault();
        getEnlaces(null, 'categoria');
    });
    $('#ordenaporenlace').on('click', function(e) {
        e.preventDefault();
        getEnlaces(null, 'href');
    });
    $('#ordenapordescripcion').on('click', function(e) {
        e.preventDefault();
        getEnlaces(null, 'descripcion');
    });
    

    $('#btRegisterCategoria').on('click', function(event) {
        var parametros = {
            nombre: $('#nombrecategoria').val().trim(),
        };
            if(parametros.nombre !== '') {
                genericAjax('ajax/registercategoria', parametros, 'post', function(json) {
                    if(json.alta > 0) {
                        getCategoriasSelect();
                    } else {
                        alert('Hay un error búscalo');
                    }
                });
            }
    });
    $('#btBusqueda').on('click', function(event) {
        var parametros = {
            filtro: $('#busqueda').val().trim(),
        };
            if(parametros.filtro !== '') {
                genericAjax('ajax/listalinks', parametros, 'get', function(json) {
                        procesarEnlaces(json.enlaces);
                        procesarPaginas(json.paginas);
                });
            }
    });
    
    $('#btRegisterLink').on('click', function(event) {
        var parametros = {
            href: $('#hrefenlace').val().trim(),
            descripcion: $('#descripcionenlace').val().trim(),
            categoria: $('#categoriaenlace').val().trim()
        };
            if(parametros.href !== ''
            && parametros.descripcion !== ''
            && parametros.categoria !== '') {
                genericAjax('ajax/registerenlace', parametros, 'post', function(json) {
                    if(json.alta > 0) {
                        getEnlaces();
                    } else {
                        alert('Hay un error búscalo');
                    }
                });
            }
    });
    
    main();

})();