import xml
import xml.etree.ElementTree as ET
import requests
import json
import re
import os

'''
whiskey
vermouth (sweet)
angostura

manhattan

gin

martini/gibson
pink gin


of

gin
vermouth (dry)
angostura
maraschino

martinez
martini
aviation (lemon juice)
casino (lemon juice)

'''
#Words to link together with another word
rightlinkers = ['carbonated','apricot','coconut','egg','hot','gomme','orgeat','ginger','soda','dashes','strawberry','de','fresh','white','dark','triple','strong','red','sweet','dry','lillet','irish','orange','grand','cherry']
leftlinkers = ['extract','sauce','purée','citron','light','de','bitter','bitters','juice','liquor', 'liqueur', 'schnapps', 'bénédictine']

def main():
    head = 'https://en.wikipedia.org'
    urls = collectURLs(head+'/wiki/List_of_IBA_official_cocktails')
    #filter nonexistant pages
    urls = list(filter(lambda x: not('?' in x), urls))
    #collect ingredients
    ingredients = []
    imgs = []
    for url in urls:
        ingredients.append(getIngredientsFromURL(head+url))
        link = "https:" + getImgFromURL(head+url)
        imgs.append("https:" + getImgFromURL(head+url))
    #process data
    #remove brackets
    ingredients = [[x.lower() for x in recipe]for recipe in ingredients]
    #remove certain words
    wordfilter = ['plain','freshly','squeezed','flower','celery','half','cut','into','wedges','two','barspoon','tom','one','teaspoon','teaspoons','leaves','leaf','cube','brown','green','optional','splash','parts','part','of','a','dashes','dash','sugar','short','old','baileys','salt','pepper','drops','simple','few','or','according','to','individual','sweetness','preference']
    ingredients = [list(filter(lambda x: not('cl' in x), recipe)) for recipe in ingredients]
    ingredients = [list(filter(lambda x: not(any(word == x for word in wordfilter)), recipe)) for recipe in ingredients]
    #Remove all junk numbers and html
    ingredients = [list(filter(lambda x: (x.replace('\'','').isalpha()) or (x.replace(' ','').isalpha()), recipe)) for recipe in ingredients]
    #link words
    ingredients = [linkStrings(recipe) for recipe in ingredients]
    #Remove duplicates
    #ingredients = [list(set(x)) for x in ingredients]
    #count occurances of ingredients
    ingredientcounter = [(x,0) for x in list(set(x for x in traverse(ingredients)))] 
    for recipe in ingredients:
        for index, ing in enumerate(ingredientcounter):
            if ing[0] in recipe:
                ingredientcounter[index] = (ing[0], ing[1] + 1)
    ingredientcounter.sort(key=lambda x: x[1], reverse=True)  
    #for tup in ingredientcounter:
    #    print(tup)
    
    #print(ingredientcounter)
    '''
    FILTERING ON CERTAIN INGREDIENTS
    showrecipes = ['orange']
    dontshowrecipes = ['syrup','egg','rum','cognac']
    count=0
    for index, recipe in enumerate(ingredients):
        if (recipe and not(any(noshow in recipe for noshow in dontshowrecipes)) and (any(match(doshow,recipe) for doshow in showrecipes))):
            count = count + 1
            print(urls[index].replace('/wiki/','--').replace('_(cocktail)',''))
            for ing in recipe:
                print(ing)
            print()
    print('>>>>>>>>>>>>COUNT = '+str(count))
    emptcounter = 0
    for index, recipe in enumerate(ingredients):
        if not recipe:
            emptcounter = emptcounter + 1
            print(urls[index])
    print(emptcounter)
    '''
    for index, recipe in enumerate(ingredients):
        if (recipe):
            print(urls[index].replace('/wiki/','--').replace('_(cocktail)',''))
            for ing in recipe:
                print(ing)
            print()
    write_all_cocktails(urls, ingredients, imgs)

def match(word, llist):
    for x in llist:
        if word in x:
            return True
    return False

def traverse(o, tree_types=(list, tuple)):
    if isinstance(o, tree_types):
        for value in o:
            for subvalue in traverse(value, tree_types):
                yield subvalue
    else:
        yield o
        
def collectURLs(rooturl):
    urllist = []
    page = requests.get(rooturl)
    #print(page.text)
    root = ET.fromstring(page.text)
    for div in root.findall('.//div[@class="div-col columns column-width"]'):
        for li in div[0]:
            if 'href' in li[0].attrib:
                urllist.append(li[0].attrib['href'])
    return urllist
                        
    
def getIngredientsFromURL(url):
    #table caption en naar dict
    ingredstrings = []
    page = requests.get(url)
    root = ET.fromstring(page.text)
    for td in root.findall('.//td[@class="ingredient"][@style="background-color: moccasin"]'):
        for ul in td:
            for li in td:
                for el in li:
                    for sub in subString(el).replace("(", " ").replace(")", " ").split():
                        ingredstrings.append(sub)
    return ingredstrings

def getImgFromURL(url):
    #table caption en naar dict
    ingredstrings = []
    page = requests.get(url)
    root = ET.fromstring(page.text)
    #print(root)
    for a in root.findall('.//a[@class="image"]'):
        for img in a:
            if (not "question" in img.attrib['src'].lower()):
                return img.attrib['src']

def linkStrings(ilist):
    tempstring = ' '.join(ilist)
    #print(tempstring)
    for ll in leftlinkers:
        tempstring = tempstring.replace(' '+ll, '.'+ll)
    for rl in rightlinkers:
        tempstring = tempstring.replace(rl+' ', rl+'.')
    
    l = tempstring.split()
    #print(l)
    for index, s in enumerate(l):
        l[index] = s.replace('.',' ')
    #print(l)
    return l


def subString(el):
    s = ""
    for item in el.itertext():
        s += item
    return s

def download_img(url):
    return requests.get(url).content

def write_all_cocktails(urls, cocktaillist, imglist):
    writedir = os.path.dirname(os.path.abspath(__file__))+"/recipes/"
    if not os.path.exists(writedir):
        os.makedirs(writedir)
    for index, recipe in enumerate(cocktaillist):
        if (recipe):
            name = urls[index].replace('/wiki/','--').replace('_(cocktail)','')
            name = re.sub('[^0-9a-zA-Z]+', ' ', name)
            name = name.replace("  ", " ").strip()
            print("write " + name)
            write_cocktail(writedir + name + ".json", recipe)
            write_img(writedir + name + ".jpg", download_img(imglist[index]))
    

def write_cocktail(outputFile, cocktail):
    encoder = json.JSONEncoder()
    with open(outputFile, 'w') as writefile:
        writefile.write(encoder.encode(cocktail))
    writefile.close()

def write_img(outputFile, byteData):
    with open(outputFile, 'wb') as f:
        f.write(byteData)
        
if __name__ == "__main__":
    main()
