#include <stdio.h>
#include <string.h>

int main(int argc, char **argv)
{
	char name[255], last[255];

	printf("Enter your first name: ");
	fgets(first, 255, stdin);
	first[strlen(first)-1] = '\0'; /* remove the newline at the end */
	

	<<<<<<< HEAD:main.c
	printf("Hello %s!\n", name);
	return 0;
=======
	printf("Now enter your last name: ");
	gets(last); /* buffer overflow? what's that? */
	
	printf("Hello %s!\n", first, last);
	retun 0;
	>>>>>>> lastname:main.c
	
}