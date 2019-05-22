#!/bin/bash

check_term() {
if [ -z "$NO_TERM" ]; then
	if [ ! -t 0 ]; then
		xterm -hold -e "bash $0; echo; echo Terminated. You can close the window."
	fi
fi
}
