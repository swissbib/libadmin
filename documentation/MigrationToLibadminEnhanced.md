# Migration from Libadmin to LibadminEnhanced (new structure)
-------------------------------------------------------------

## Export from Libadmin

Only data no structure :

```
sudo mysqldump --no-create-info --complete-insert libadmin > libadmin-dump-20180614.sql
```


## Export CSV from Excel files for the new information

Separator ;. No "" around text.

Save this to :
documentation/database/data/libadmin.institutions.csv
documentation/database/data/admininstitution.csv

Be careful : 
- A256 has a ";" in column AB (rechnungadresse)
- N06 as well
- N07 as well
- RE31050 has a ; in colum email
- LUMH1 have HSLUMU ; IDSLUKB in column AU
- LUMH3 idem
- LUMHS idem
- The institutions A353, sbi, E89 doesn't exist any more in libadmin


## Create new Database

This will create an empty libadminenhanced database with the new structure

```
sudo mysql < ./documentation/database/libadminenhanced.schema.sql
```


## Import old Information from Libadmin

```
sudo mysql libadminenhanced < ~/Documents/mycloud/swissbib/libadmin/libadmin-dump-20180614.sql
```


## Change config of libadmin 

In config/autoload/local.php

To use the database libadminenhanced instead of libadmin

## Import Information from Excel

Does that overwrite some fields of the previous step ? It should only add new info !!!

This will import in the db forom config/autoload/local.php

```
cd libadmin
php public/index.php loaddata institution ../documentation/database/data/libadmin.institutions.new.csv
php public/index.php loaddata admininstitution ../documentation/database/data/admininstitution.new.csv
```



## Remove unused columns ? 

The address might have been updated in libadmin and not in the excel. Be careful with this step.



