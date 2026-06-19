# Petit script Python basique pour verifier des donnees de catalogue.
# Le site ne depend pas de ce fichier. Il sert seulement a s'entrainer.

produits = [
    {"nom": "PC portable NovaBook 14", "prix": 629.90, "stock": 8},
    {"nom": "Pack gaming Starter", "prix": 89.90, "stock": 15},
    {"nom": "Clavier mecanique Compact", "prix": 49.90, "stock": 20},
    {"nom": "Souris sans fil Pulse", "prix": 24.90, "stock": 30},
    {"nom": "Casque audio Study Pro", "prix": 59.90, "stock": 12},
    {"nom": "Lampe de bureau LED", "prix": 34.90, "stock": 10},
]

erreurs = []
noms_deja_vus = []
stock_total = 0

for produit in produits:
    nom = produit["nom"]
    prix = produit["prix"]
    stock = produit["stock"]

    if nom == "":
        erreurs.append("Un produit n'a pas de nom.")

    if prix <= 0:
        erreurs.append("Le produit " + nom + " a un prix incorrect.")

    if stock < 0:
        erreurs.append("Le produit " + nom + " a un stock negatif.")

    if nom in noms_deja_vus:
        erreurs.append("Le produit " + nom + " est en double.")
    else:
        noms_deja_vus.append(nom)

    stock_total = stock_total + stock

if len(erreurs) == 0:
    print("Catalogue verifie : aucune erreur trouvee.")
else:
    print("Erreurs trouvees :")
    for erreur in erreurs:
        print("- " + erreur)

print("Nombre de produits :", len(produits))
print("Stock total :", stock_total)
