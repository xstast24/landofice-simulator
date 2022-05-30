import clipboard
from math import e

# TODO zkusit dat x+1, aby se fitovalo na "Xte nasledujici dobyti" a ne na "X predchozich dobyti"
# key = pocet predchozich dobyti, value = pocet jednotek v jeskyni
values = {0: 500, 1: 1150, 2: 2077, 3: 3185, 4: 4460, 5: 5909, 6: 7549, 7: 9413, 8: 11543, 9: 14002, 10: 16870, 11: 20254, 12: 24294,
          14: 35138, 16: 51651, 22: 188397, 39: 13950398, 40: 18119918, 45: 67123902, 46: 87241968, 47: 113394853, 54: 711157175}
x_values = values.keys()
x_values_string_copy_paste = ' '.join(map(str, x_values))
y_values = values.values()
y_values_string_copy_paste = ' '.join(map(str, y_values))


# MYCURVEFIT.COM
x_axis = [str(value) for value in x_values]
y_axis = [str(value) for value in y_values]
mycurvefit_copy_paste = ''
for x, y in values.items():
    if x == 7 or x == 11:
        continue # need to ignore 2 values, cos free limit of values is 20
    mycurvefit_copy_paste += f'{x} {y}\n'
# clipboard.copy(copy_data)  # paste this to data section
# mycurvefit.com results
def exponential(x):
    # nonlinear - exponential - basic: y = 11781.96 + 502.2433*e^(+0.2622834*x)
    return 11781.96 + 502.2433 * e**(0.2622834*x)
def bell_curve(x):
    # nonlinear - gaussian - bell curve (same as cubic spline - auto smoothed spline): y = 6567974000000000000*e^(-(x - 233.019)^2/(2*26.42571^2))
    return 6567974000000000000 * e**(-(x - 233.019)**2/(2*26.42571**2))


# WOLFRAM ALPHA
def x4(x):
    # x^4 polynomial
    return 0.734343 * x**4 - 13.9057 * x**3 + 184.062 * x**2 + 203.12 * x + 121


clipboard.copy(x_values_string_copy_paste)
exit()

# verification test
methods = [x4, exponential, bell_curve]
for x, y in values.items():
    results = f'orig {y:10}'
    diffs = f'diff {0:10}'
    for method in methods:
        result = method(x)
        results += f'\t{method.__name__} {round(result):10}'
        diffs += f'\t{method.__name__} {round(result-y):10}'
    print(results)
    print(diffs, '\n')

exit()