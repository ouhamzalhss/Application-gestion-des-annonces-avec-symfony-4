$('#add-image').click(function(){
    // je recupere lindex des champs que je vais inserer
    const index = +$('#counter').val();
     // je recupere le protype des entrees
    const templ = $('#ad_images').data('prototype').replace(/_name_/g,index);
    // j'injecte ce code au sein de div
    $('#ad_images').append(templ);

    $('#counter').val(index + 1);

    handledelete();
});

function handledelete(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();

    });
}
// Permet de mise a jour le compte des images 
function updateCounter(){
    const count = +$('#ad_images div.form-group').length;
    $('#counter').val(count);
}

handledelete();
updateCounter();