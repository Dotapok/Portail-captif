// Révéler/Masquer le mot de passe
function togglePassword() {
    let pwd = document.getElementById("password");
    if (pwd.type === "password") {
        pwd.type = "text";
    } else {
        pwd.type = "password";
    }
}

// Gestion multilingue
function changeLang(lang) {
    fetch(`../lang/${lang}.json`)
        .then(response => response.json())
        .then(data => {
            if(document.getElementById('titre_bienvenue')) 
                document.getElementById('titre_bienvenue').innerText = data.titre_bienvenue;
            if(document.getElementById('sous_titre')) 
                document.getElementById('sous_titre').innerText = data.sous_titre;
            if(document.getElementById('statut_message')) 
                document.getElementById('statut_message').innerText = data.statut_message;
            if(document.getElementById('password')) 
                document.getElementById('password').placeholder = data.champ_mdp;
            if(document.getElementById('bouton_connexion')) 
                document.getElementById('bouton_connexion').innerText = data.bouton_connexion;
            if(document.getElementById('statut_connecte')) 
                document.getElementById('statut_connecte').innerText = data.statut_connecte;
            if(document.getElementById('bouton_deconnexion')) 
                document.getElementById('bouton_deconnexion').innerText = data.bouton_deconnexion;
        });
}

// Charger la langue du navigateur au démarrage
window.onload = () => {
    let userLang = navigator.language || navigator.userLanguage; 
    let langCode = userLang.startsWith('fr') ? 'fr' : 'en';
    changeLang(langCode);
};