/**
 * This file contains reusable javascript functions/utilities, that are NOT dependent on the game. Any generic helper methods etc.
 * */

/**
 * Returns a random integer between min (inclusive) and max (inclusive). Source: https://stackoverflow.com/a/1527820/7684041
 * The value is no lower than min (or the next integer greater than min if min isn't an integer)
 * and no greater than max (or the next integer lower than max if max isn't an integer)
 */
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}