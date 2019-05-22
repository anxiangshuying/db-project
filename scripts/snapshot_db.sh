#!/bin/bash
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd "$SCRIPTPATH/../"
source scripts/helpers/macros.sh
check_term

schemas_file="sql/schemas"
entries_file="sql/entries"

handle_duplicate() {
	name="$1"
	if [[ -e $name.sql ]] ; then
	    i=0
	    while [[ -e $name.$i.sql ]] ; do
		let i++
	    done
	    name=$1.$i.sql
	fi
	echo $name
}
datetime="$(date +"%Y-%m-%d_%H-%M-%S")"

$(python3 scripts/get_yaml.py "config/db.yaml" "app")
mysql_cmd="-h$server -u$username -p$password"
if [ -n "$LOCAL_DBPROJECT" ]; then
	if [ "$1" = "last_is_file" ]; then
		if ! mysql $mysql_cmd -e "use $db"; then
			exit
		fi
		sav_schemas="sql/backup/$datetime.schemas.sql"
		sav_entries="sql/backup/$datetime.entries.sql"
	else
		if [ -e "$schemas_file.sql" ]; then
			mv "$schemas_file.sql" "sql/backup/$datetime.schemas.sql"
			sav_schemas="$schemas_file.sql"
		fi
		if [ -e "$entries_file.sql" ]; then
			mv "$entries_file.sql" "sql/backup/$datetime.entries.sql"
			sav_entries="$entries_file.sql"
		fi
	fi
	mysqldump $mysql_cmd --no-data --no-create-db $db > $sav_schemas

	mysqldump $mysql_cmd --no-create-info --no-create-db $db > $sav_entries
else
	vagrant ssh -c "export LOCAL_DBPROJECT=true; bash /vagrant/scripts/snapshot_db.sh"
fi
