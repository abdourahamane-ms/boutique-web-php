# Petit script volontairement simple.
# Il ne sert pas au fonctionnement du site, mais a verifier rapidement
# quelques donnees de produits pendant la preparation du projet.

produits = [
    {"nom": "PC portable NovaBook 14", "prix": 629.90, "stock": 9},
    {"nom": "Pack gaming Starter", "prix": 89.90, "stock": 15},
    {"nom": "Clavier mecanique Compact", "prix": 49.90, "stock": 24},
    {"nom": "Souris sans fil Pulse", "prix": 24.90, "stock": 32},
    {"nom": "Casque audio Study Pro", "prix": 59.90, "stock": 18},
]

total_stock = 0
produit_plus_cher = produits[0]

for produit in produits:
    total_stock = total_stock + produit["stock"]

    if produit["prix"] > produit_plus_cher["prix"]:
        produit_plus_cher = produit

print("Nombre de produits verifies :", len(produits))
print("Stock total :", total_stock)
print("Produit le plus cher :", produit_plus_cher["nom"])
