# php-oxo
Purely functional implementation of OXO in PHP

This is purely a proof of concept: I've trued to build this from minimal building blocks of pure functions and a single-linked list. Along the way implementing left and right folds, maps, max, min, reverse etc etc.

The code can be a little difficult to follow because a single-linked list is all I've allowed myself (apart from a class (which would be a struct if php had them) to define the linked list (container and pointer to the next element in the list)).

The board is implemented as a linked list where every element is a square number and a link to a piece ('X', '0' or ' ');
The strategy.php runs the game from the computer's point of view by traversing the move tree and implementing the minimax algorithm.

Square numbers are:

0 | 1 | 2

3 | 4 | 5

6 | 7 | 8

Quirks - occasionally the game will eschrow an immediate win, in this case the win will come eventually.
The implementatin is slow so try switching off xdebug.
The code would be much better if PHP had a better type system and made it easier to compose and pipe functions. See the ruby version for a better implementation.
