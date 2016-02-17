# Dynamic Gif generator

This is essentially a fork of Alex Ilhan's [Email Countdown](https://github.com/Omgitsonlyalex/EmailCountdown) repository which he writes about [here](https://litmus.com/community/learning/27-how-to-add-a-countdown-timer-to-your-email), with a few key differences:

* Built for use with an API, instead of time
    - Depends on an array of 24 different numbers, value comes by passing the current hour as the key
    - It counts the difference between two numbers.
* Outputs a gif to the attached folder instead of generating one in browser
* Different style text (white and outlined)


## Getting started
First, the whole things runs off of php so be sure to run things through a local server, like MAMP. Also, you'll want to replace background-3.png with your desired background image. Below are a few lines you'll want to look into before getting started.

* 61: Font file - upload your own font file to be used here depending on which font you want
* 56-63: Font positioning and style - created centered white outlined text, modify depending on image preferences
* 39-42: Simply a sample array of data, modify as necessary

If using an API:

* 22-34: Uncomment and modify url based on API
* 38-42: uncomment line 38 and commment out sample data in 39-42


## Additional documention
For any additiaonl info, please reference the Email Coundown repo mentioned above, or Alex's writeup, also listed above.

## Disclaimer
I am by no means a php expert, so I'm 100% positive, there's a better way to do this, this is what got the job done for me.