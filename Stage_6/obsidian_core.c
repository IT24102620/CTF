#include <stdio.h>
#include <string.h>

#define XOR_KEY 0x5A

/* flag XOR 0x5A */
static const unsigned char enc_flag[] = {
    0x35, 0x38, 0x29, 0x33, 0x3E, 0x33, 0x3B, 0x34, 
    0x21, 0x22, 0x6A, 0x28, 0x05, 0x39, 0x6A, 0x28, 
    0x69, 0x05, 0x36, 0x6A, 0x3D, 0x6B, 0x39, 0x05, 
    0x28, 0x69, 0x2C, 0x69, 0x28, 0x29, 0x69, 0x3E, 0x27
};

/* Passphrase XOR 0x5A */
static const unsigned char enc_pass[] = {
    0x28, 0x69, 0x2C, 0x69, 0x28, 0x29, 0x69
};

/* Passphrase check: XORs the user input and compares it directly to the encrypted password array */
static int check_pass(const char *input) {
    // Fail immediately if the length is wrong
    if (strlen(input) != sizeof(enc_pass)) {
        return 0;
    }
    
    // Check each character against the XOR'd array
    for (size_t i = 0; i < sizeof(enc_pass); i++) {
        if ((input[i] ^ XOR_KEY) != enc_pass[i]) {
            return 0; // Mismatch found
        }
    }
    return 1; // Valid passphrase
}

static void reveal_flag(void) {
    char flag[sizeof(enc_flag) + 1];
    for (size_t i = 0; i < sizeof(enc_flag); i++) {
        flag[i] = (char)(enc_flag[i] ^ XOR_KEY);
    }
    flag[sizeof(enc_flag)] = '\0';
    printf("\n[+] Correct! Here is your flag: %s\n", flag);
}

int main(void) {
    char input[64];

    printf("========================\n");
    printf("=== Reverse Me ===\n");
    printf("========================\n");
    printf("Enter the passphrase: ");
    
    if (scanf("%63s", input) != 1) {
        return 1;
    }

    if (check_pass(input)) {
        reveal_flag();
    } else {
        printf("\n[-] Wrong passphrase. Try harder!\n");
    }
    
    return 0;
}
