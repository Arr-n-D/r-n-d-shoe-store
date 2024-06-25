# Shoe Store
Application en PHP Laravel permettant de tracker les inventory updates pour différents shoe stores

## Description
Lorsqu'un websocket message arrive, le client trouvé dans WebSocketClient.php dispatch un event qui sera attrapé par le InventoryUpdatedEventListener, qui storera les transactions à entrées dans la base de données. 

Une scheduled task roule à intervales régulières qui elle va s'occuper de mettre à jour la base de données.

Cette décision vient du fait que de mettre à jour la base de données à chaque fois qu'on reçoit un update d'inventaire est peu performant.

Certaines données sont storées dans la cache Redis pour éviter d'appeler la base de données trop souvent

Un API REST avec deux routes est fourni, de plus qu'une intégration Discord permettant de voir l'inventaire avec un délai de 30 secondes, de plus qu'un alerting Discord pour les souliers avec un inventaire faible. 

Les fichiers suivants sont à regarder

ProcessInventoryUpdates
UpdateStoreInventory
InventoryUpdatedEventListener
WebSocketClient
InventoryController
StoreController
InventoryUpdatedEvent
TestSocket
InventoryResource
StoreResource
WebSocketClientServiceProvider
console in /routes
api in /routes







