Flag: obsidian{ph4nt0m_v0lum3_c4rv3d}

Artifact Provided:
evidence_drive.dd

// 1. Recover the deleted archive in Autopsy (GUI Steps)
- Open Autopsy -> New Case -> Add Data Source -> Disk Image or VM File
- Select "evidence_drive.dd"
- Navigate to: Data Sources -> evidence_drive.dd -> $OrphanFiles (or Views -> Deleted Files)
- Right-click "secret_backup.zip" -> Extract File(s)

// 2. Extract the password hash from the recovered ZIP file
zip2john secret_backup.zip > zip.hash

// 3. Crack the ZIP password using John the Ripper and rockyou.txt
john --wordlist=/usr/share/wordlists/rockyou.txt zip.hash

// 4. View the cracked password
john --show zip.hash

// 5. Extract the password-protected ZIP file (enter cracked password when prompted)
unzip secret_backup.zip

// 6. View the obfuscated (Base64) contents inside flag.txt
cat flag.txt

// 7. Decode the Base64 string to reveal the flag
cat flag.txt | base64 -d