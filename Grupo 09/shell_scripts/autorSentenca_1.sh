#!/bin/bash

read autorSentenca

cd ../sentenca/

pdfgrep -rin autor:\ "$autorSentenca*"


