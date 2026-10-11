#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main() {
    // Set real UID to root so /bin/sh doesn't drop privileges when system() is called
    setuid(0);
    setgid(0);
    
    printf("Obsidian Admin Diagnostic: Fetching recent authentication logs...\n");
    
    // VULNERABILITY: Executing 'cat' without an absolute path.
    // It will blindly trust the user's $PATH variable to find 'cat'.
    system("cat /var/log/obsidian_auth.log");
    
    return 0;
}
