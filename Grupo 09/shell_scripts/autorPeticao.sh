#!/bin/bash

read autor

cd ../peticoes/

pdfgrep -rin autor:\ "$autor*"


