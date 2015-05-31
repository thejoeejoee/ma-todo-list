Postup pro rozběhnutí:
1. adresáře log/, temp/ a www/webtemp/ učinit zapisovatelnými pro PHP, na kterém bude běžet aplikace
2. pomocí composeru nainstalovat závislosti z composer.json do vendor/
3. inicializovat databázi soubory db_structure.sql a db_data.sql
4. do app/config/config.local.neon umístit přístupové údaje do databáze
5. nasměrovat webserver do www/ a používat