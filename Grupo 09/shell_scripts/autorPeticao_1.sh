#!/bin/bash

read autorPeticao

cd ../peticao/

pdfgrep -rin autor:\ "$autorPeticao*"

pwd


