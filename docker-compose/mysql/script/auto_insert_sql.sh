#!/bin/bash
echo '############################';
echo '# Importando datos a mysql #';
echo '############################';

for entry in `ls .`; do

    if [ $entry != 'auto_insert_sql.sh' ]
        then
            mysql -u root covid19 < $entry
    fi

done

echo 'Datos importados';
