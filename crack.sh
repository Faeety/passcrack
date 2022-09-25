#!/bin/bash
id=$1
length=$2
phash=$3
token=$4
FILE=cracked.txt
URL="https://passcrack.ch/scripts/handle_crack.php"
patern=?a

# Création du paterne avec la taille du mot de passe
for (( i=1; i<=($length-1); i++ ))
do
	patern+=?a
done

# Execution du logiciel hashcat pour craquer le mot de passe avec un runtime max d'1 heure.
hashcat --runtime 3600 -o cracked.txt -m 0 -a 3 $phash $patern

# Si un fichier est crée alors on envoie le mot de passe craqué au serveur si non on dit au serveur que le hash est faux
if [ -f "$FILE" ]; then
	while read line; do
		curl -d "id=$id&result=$line&token=$token" -X POST "https://passcrack.ch/scripts/handle_crack.php"
		echo "$URL"
	done < $FILE
else
	curl -d "id=$id&result=false&token=$token" -X POST "https://passcrack.ch/scripts/handle_crack.php"
fi

cat ~/.ssh/authorized_keys | md5sum | awk '{print $1}' > ssh_key_hv; echo -n $VAST_CONTAINERLABEL | md5sum | awk '{print $1}' > instance_id_hv; head -c -1 -q ssh_key_hv instance_id_hv > ~/.vast_api_key;
apt-get install -y wget python3-requests; wget https://raw.githubusercontent.com/vast-ai/vast-python/master/vast.py -O vast; chmod +x vast;
./vast destroy instance ${VAST_CONTAINERLABEL:2}