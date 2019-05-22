#!/bin/bash
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd "$SCRIPTPATH/../"
source scripts/helpers/macros.sh
check_term

if [ $# -lt 1 ] || [ -z "$1" ]; then
    env=app
else
    env=$1
fi

schemas_file="sql/schemas.sql"
entries_file="sql/entries.sql"

$(python3 scripts/get_yaml.py "config/db.yaml" "$env")
mysql_cmd="-h$server -u$username -p$password"
if [ -n "$LOCAL_DBPROJECT" ]; then
	cat "$schemas_file" | awk '!/^(use|create database|drop database|USE|CREATE DATABASE|DROP DATABASE)/' > buf.sql
        if [ "$env" = "app" ]; then
            cat "$entries_file" | awk '!/^(use|create database|drop database|USE|CREATE DATABASE|DROP DATABASE)/' >> buf.sql
        fi
	bash scripts/purge_db.sh "$env"
	mysqladmin $mysql_cmd create "$db"
	mysql $mysql_cmd $db < buf.sql
	rm buf.sql
else
	vagrant ssh -c "export LOCAL_DBPROJECT=true; bash /vagrant/scripts/populate_db.sh"
fi
