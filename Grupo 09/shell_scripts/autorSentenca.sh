#!/bin/bash

read autor

cd ../sentenca/

pdfgrep -rin autor:\ "$autor*"


