$(function () {
        $('#inicio_inscricao').datetimepicker({
            locale: 'pt-br',
            format: 'DD/MM/YYYY'
        });
        $('#fim_inscricao').datetimepicker({
            locale: 'pt-br',
            format: 'DD/MM/YYYY'
        });
        $('#prazo_carta').datetimepicker({
            locale: 'pt-br',
            format: 'DD/MM/YYYY'
        });
        $('#edital_ano').datetimepicker({
            locale: 'pt-br',
            format: 'YYYY',
            viewMode: 'years'
        });
        $('#data_nascimento').datetimepicker({
            locale: 'pt-br',
            format: 'DD/MM/YYYY',
            viewMode: 'years'
        });
        $('#ano_conclusao_graduacao').datetimepicker({
            locale: 'pt-br',
            format: 'YYYY',
            viewMode: 'years'
        });
        $('#ano_titulacao').datetimepicker({
            locale: 'pt-br',
            format: 'YYYY',
            viewMode: 'years'
        });
        $('#ano_conclusao_pos').datetimepicker({
            locale: 'pt-br',
            format: 'YYYY',
            viewMode: 'years'
        });
});

$('#disciplinas').click(function() {
  var checkedStatus = this.checked;
  $('#disciplinas tbody tr').find('td :checkbox').each(function() {
    $(this).prop('checked', checkedStatus);
  });
});