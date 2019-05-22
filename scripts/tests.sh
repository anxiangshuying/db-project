#!/bin/bash
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd "$SCRIPTPATH/../"
source scripts/helpers/macros.sh
check_term

if [ -n "$LOCAL_DBPROJECT" ]; then
	bash scripts/populate_db.sh "test"
	for i in "tests/"*; do
		echo $i
		echo -------
		phpunit --bootstrap "autoload.php" "$i"
	done
else
	vagrant ssh -c "export LOCAL_DBPROJECT=true; bash /vagrant/scripts/tests.sh"
fi
