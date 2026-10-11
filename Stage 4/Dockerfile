# Use a standard Ubuntu base image
FROM ubuntu:22.04

# Install OpenSSH server and GCC (for compiling the C code)
RUN apt-get update && apt-get install -y openssh-server gcc && rm -rf /var/lib/apt/lists/*

# Configure SSH directory
RUN mkdir /var/run/sshd

# Create the low-privileged user using the credentials from Stage 3
RUN useradd -m -s /bin/bash sysadmin_01
RUN echo "sysadmin_01:Sup3rS3cr3tSSH!" | chpasswd

# Plant the Stage 4 flag in the root directory
RUN echo "obsidian{su1d_p4th_h1j4ck_r00t}" > /root/flag4.txt
RUN chmod 600 /root/flag4.txt

# Create a fake log file for the binary to read
RUN echo "10:45:01 - root session opened\n10:50:33 - root session closed" > /var/log/obsidian_auth.log
RUN chmod 644 /var/log/obsidian_auth.log

# Copy the vulnerable C source code into the container
COPY view_logs.c /tmp/view_logs.c

# Compile the binary, move it to a standard bin path, and apply the SUID bit
RUN gcc /tmp/view_logs.c -o /usr/local/bin/view_logs
RUN chown root:root /usr/local/bin/view_logs
# The '4' in 4755 sets the SUID bit, making it execute as the owner (root)
RUN chmod 4755 /usr/local/bin/view_logs

# Clean up the source code so players can't just read it directly
RUN rm /tmp/view_logs.c

# Expose the standard SSH port
EXPOSE 22

# Start the SSH service
CMD ["/usr/sbin/sshd", "-D"]