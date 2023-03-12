# loanCalculate
1. В завданні є помилка в документації.
    - якщо поле телефон не обов'язкове, то і визначити limitItog можна тільки якщо користува ввів номер телефону.
2. Не зрозуміло для чого нам requestLimit якщо це не записується до бази даних. Тому я просто додам ще один стовпчик.

3. Якщо limitItog більше ніж requestLimit, то обмежити значенням requestLimit.
не зрозуміло чого якщо кредитний ліміт більший за бажану суму кредиту то треба баану суму зменшувати
4. зробив інакше
5. 
controller App\Http\Controllers\DecisionClientController
model App\Models\Clients
table database\migrations\2023_03_09_155352_create_client_table.php
