// URL para el API
var rootURL = "http://localhost/apitsaya/api/modelos";
var currentModelo;

// Muestra todos los registros cuando carga la interfaz
findAll();

$('#btnDelete').hide();
$('#btnSearch').click(function() {
	search($('#searchKey').val());
	return false;
});

$('#searchKey').keypress(function(e){
	if(e.which == 13) {
		search($('#searchKey').val());
		e.preventDefault();
		return false;
    }
});

$('#btnAdd').click(function() {
	newModelo();
	return false;
});

$('#btnSave').click(function() {
	if ($('#modeloId').val() == '')
		addModelo();
	else
		updateModelo();
	return false;
});

$('#btnDelete').click(function() {
	deleteModelo();
	return false;
});

$('#modeloList a').live('click', function() {
	findById($(this).data('identity'));
});

function search(searchKey) {
	if (searchKey == '') 
		findAll();
	else
		findByName(searchKey);
}

function newModelo() {
	$('#btnDelete').hide();
	currentModelo = {};
	renderDetails(currentModelo); // Despliega un formulario vacio
}

function findAll() {
	console.log('findAll');
	$.ajax({
		type: 'GET',
		url: rootURL,
		dataType: "json",
		success: renderList
	});
}

function findByName(searchKey) {
	console.log('findByName: ' + searchKey);
	$.ajax({
		type: 'GET',
		url: rootURL + '/search/' + searchKey,
		dataType: "json",
		success: renderList 
	});
}

function findById(id) {
	console.log('findById: ' + id);
	$.ajax({
		type: 'GET',
		url: rootURL + '/' + id,
		dataType: "json",
		success: function(data){
			$('#btnDelete').show();
			console.log('findById success: ' + data.modelo_nombre);
			currentModelo = data;
			renderDetails(currentModelo);
		}
	});
}

function addModelo() {
	console.log('addModelo');
	$.ajax({
		type: 'POST',
		contentType: 'application/json',
		url: rootURL,
		dataType: "json",
		data: formToJSON(),
		success: function(data, textStatus, jqXHR){
			alert('Modelo creado exitosamente!');
			$('#btnDelete').show();
			$('#modeloId').val(data.id);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('addModelo error: ' + textStatus);
		}
	});
}

function updateModelo() {
	console.log('updateModelo');
	$.ajax({
		type: 'PUT',
		contentType: 'application/json',
		url: rootURL + '/' + $('#modeloId').val(),
		dataType: "json",
		data: formToJSON(),
		success: function(data, textStatus, jqXHR){
			alert('Modelo modificado exitosamente!');
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('updateModelo error: ' + textStatus);
		}
	});
}

function deleteModelo() {
	console.log('deleteModelo');
	$.ajax({
		type: 'DELETE',
		url: rootURL + '/' + $('#modeloId').val(),
		success: function(data, textStatus, jqXHR){
			alert('Modelo eliminado exitosamente!');
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('deleteModelo error');
		}
	});
}

function renderList(data) {
	var list = data == null ? [] : (data.modelos instanceof Array ? data.modelo : [data.modelo]);

	$('#modeloList li').remove();
	$.each(list, function(index, modelos) {
		$('#modeloList').append('<li><a href="#" data-identity="' + modelos.id + '">'+modelos.modelo_nombre+'</a></li>');
	});
}

function renderDetails(modelos) {
	$('#modeloId').val(modelos.id);
	$('#nombre').val(modelos.modelo_nombre);
	$('#ruta').val(modelos.modelo_ruta);
	$('#categoria').val(modelos.categoria);
	$('#negocio').val(modelos.negocio);
}

function formToJSON() {
	return JSON.stringify({
		"id": $('#modeloId').val(), 
		"nombre": $('#nombre').val(), 
		"ruta": $('#ruta').val(),
		"categoria": $('#categoria').val(),
		"negocio": $('#negocio').val()
		});
}
