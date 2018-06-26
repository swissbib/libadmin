# Migration from Libadmin to LibadminEnhanced (new structure)
-------------------------------------------------------------

## Export from Libadmin

Only data no structure :

```
sudo mysqldump --no-create-info --complete-insert libadmin > libadmin-dump-20180626.sql
```


## Export CSV from Excel files for the new information

Separator ;. No "" around text.

Save this to :
documentation/database/data/libadmin.institutions.csv
documentation/database/data/admininstitution.csv

Be careful : 
- for museum geneve : ayer is in the wrong column
- for admin ABNKB, columns C and AB are on two lines
- The institutions A353, sbi, E89 doesn't exist any more in libadmin. They should be removed from the "zugehörige institutionen" of the admininstitution table
- in admin : kreditoren@ twice in the wrong column

## Create new Database

This will update the structure of the db libadmintest with the new one and remove the content of the tables

```
sudo mysql libadmintest < ./documentation/database/libadminenhanced.schema.sql
```


## Import old Information from Libadmin

```
sudo mysql libadmintest < ~/Documents/mycloud/swissbib/libadmin/libadmin-dump-20180626.sql
```


## Change config of libadmin 

In config/autoload/local.php

To use the database libadminenhanced instead of libadmin

## Import Information from Excel

Does that overwrite some fields of the previous step ? It should only add new info !!!

This will import in the db from config/autoload/local.php

```
cd libadmin
php public/index.php loaddata institution ../documentation/database/data/libadmin.institutions.csv
php public/index.php loaddata admininstitution ../documentation/database/data/admininstitution.csv
```



## Remove unused columns ? 

The address might have been updated in libadmin and not in the excel. Be careful with this step.

## In one step 
```
sudo mysql < ./documentation/database/libadminenhanced.schema.sql
sudo mysql libadminenhanced < ~/Documents/mycloud/swissbib/libadmin/libadmin-dump-20180626.sql
php public/index.php loaddata institution ../documentation/database/data/libadmin.institutions.csv
php public/index.php loaddata admininstitution ../documentation/database/data/admininstitution.csv
```



