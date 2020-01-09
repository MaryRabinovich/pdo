# pdo
Some useful unifying functions for pdo users

1) other architectural blocks can call these functions 
without expliciting sql parameters;

2) "$pdo->prepare($sql)" is used only against injections,
for the moment there are no placeholders.
