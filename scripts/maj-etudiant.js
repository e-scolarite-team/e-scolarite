$(document).ready(function(){
    $("#nom").css('width','50%');    
    $("#nom").keyup(rechercher);
    $("#rechercher").click(function(e){ e.preventDefault(); });
});

var oldSearch=null;

function rechercher(e)
{
    var n = $('#nom').val();
    if( n != '' )
    {
        if(oldSearch!=n)
        {
            $("#ajax-loader").show();
            var jqxhr = $.post('recherche.php',{nom : n});
            jqxhr.done(afficher);
            jqxhr.always(function(){$("#ajax-loader").hide();})
        }
    }
    else
    {
        $('#suggestion').empty();
    }
    oldSearch = n;
}

function afficher(data)
{
    if(data!='')
    {
        $('#suggestion').empty();
        var temp = null;
        $.each( data , function(i,item){
            temp = '<div class="profile"><h6>' + item.cne + '</h6><p>' + item.nom + '</p><p>' + item.prenom + '</p></div>';  
            $(temp).appendTo("#suggestion").click(function (){
                var cne = $(this).find('h6').text();
                charger_aside(cne);
            });
        });
        // Premier effet visuel (lent)
        /*$('.profile').first().fadeIn('fast',function affiherSuivant(){
           $(this).next('.profile').fadeIn('fast', affiherSuivant); 
        });*/
    }
    else
    {
        $('#suggestion').empty();
    }
}

// charger les infos de l'étudiant à modifer
function charger_aside(cne)
{
    if( cne != '' )
    {
        $("#ajax-loader").show();
        var jqxhr = $.post('recherche.php',{"cne" : cne});
        jqxhr.done(maj_aside);
        jqxhr.always(function(){$("#ajax-loader").hide();})
    }
    else
    {
        $('#maj_aside').empty();
        $('#maj_aside').hide();
    }
}

// generer contenu à modifier
function maj_aside(data)
{
    if(data=='')
    {
        $('#maj_aside').empty();
    }
    else
    {
        $('#maj_aside').empty();
        $('#maj_aside').append('<div id="bt-fermer" class="bt-fermer"><img src="img/fermer.png" alt="fermer" /></div>');
        $('#maj_aside').append('<label>CNE</label>');
        $('#maj_aside').append('<input type="text" name="cne" value="'+ data[0].cne +'" />');
        $('#maj_aside').append('<input type="hidden" name="cne_old" value="'+ data[0].cne +'" />');
        $('#maj_aside').append('<label>Nom</label>');
        $('#maj_aside').append('<input type="text" name="nom" value="'+data[0].nom +'" />');
        $('#maj_aside').append('<label>Prénom</label>');
        $('#maj_aside').append('<input type="text" name="prenom" value="'+data[0].prenom  +'" />');
        $('#maj_aside').append('<label>Date de naissance</label>');
        $('#maj_aside').append('<input type="text" name="date" value="'+ data[0].datenaiss+'" />');
        $('#maj_aside').append('<label>الاسم العائلي</label>');
        $('#maj_aside').append('<input type="text" name="nomar" value="'+ data[0].nomar+'" />');
        $('#maj_aside').append('<label>الاسم الشخصي</label>');
        $('#maj_aside').append('<input type="text" name="prenomar" value="'+ data[0].prenomar+'" />');
        $('#maj_aside').append('<input type="submit" id="valider" class="valider" value="Valider" />');
        $('#main-menu').hide('slow',function(){
        $('#maj_aside').show('slow');});
        $("#valider").click(function(e){ e.preventDefault();mise_a_jour(); });
        $("#bt-fermer").click(function(){ $('#maj_aside').hide('slow',function(){$('#main-menu').show('slow');}); });
    }
}

function mise_a_jour()
{
    cne = $('#maj_aside').find('input[name="cne"]').val();
    cne_old = $('#maj_aside').find('input[name="cne_old"]').val();
    nom = $('#maj_aside').find('input[name="nom"]').val();
    prenom = $('#maj_aside').find('input[name="prenom"]').val();
    datenaiss = $('#maj_aside').find('input[name="date"]').val();
    nomar = $('#maj_aside').find('input[name="nomar"]').val();
    prenomar = $('#maj_aside').find('input[name="prenomar"]').val();
    if( cne_old != '' )
    {
        $("#ajax-loader").show();
        var jqxhr = $.post('recherche.php',{"cne_old" : cne_old,"cne":cne,"nom":nom,"prenom":prenom,"datenaiss":datenaiss,"nomar":nomar,"prenomar":prenomar});
        jqxhr.done(function(data){
            if(data=="100")
            {
                $('#maj_aside').append('<div class="info">Action terminée avec succès!</div>');
            }
            else if(data=='104')
            {
                $('#maj_aside').append('<div class="erreur">Erreur dans la base de données.</div>');
            }
        });
        jqxhr.fail(function(){
            $('#maj_aside').append('<div class="erreur">Erreur de traitement</div>');
        });
        jqxhr.always(function(){$("#ajax-loader").hide();});
    }
}