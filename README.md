# Smart Insurance (made by PepeBurro)

We provide a REST-ful HTTPS API for automated interfaces between insurance company systems and our service. Our API uses predictable, resource-oriented URI's to make methods available and HTTP response codes to indicate errors.

Our API methods return machine readable responses in JSON format, including error conditions.

## Available methods

To call the API you need to call one of the methods listed below with the specified parameters. 

Listed methods:

### add_contract

To add contract to the system system. Input paramaters:
-contract object (contract), including fields: 
	-'type_id',
        -'order_id',
	-'customer',
	-'status',
	-'provider_id',
	-'claim_date'

### get_by_id

To get contract full details by id, input parameters:
    *conract id (id)
    
### get_by_type_id

To get list of contracts of specific type full details by type_id, input parameters:
    *type id (type_id)

### get_by_type_name

To get list of contracts of specific customer full details by customer name, input parameters:
    *customer name (customer)

### get_by_type_status

To get list of claims of specific status full details by status, input parameters:
    *claim status (status)
    
### get_all

To get all contracts full details, no input parameters.
