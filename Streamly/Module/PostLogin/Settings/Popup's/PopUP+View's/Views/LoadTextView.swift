//
//  LoadTextView.swift
//  Streamly
//
//  Created by Rohit Singh on 6/11/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class LoadTextView: NibView {

    //MARK: Outlets

    @IBOutlet weak var okButton:UIButton!
    @IBOutlet weak var textView:UITextView!

    //MARK: Properties

    weak var delegate:CustomViews?

    override var preferredFocusedView: UIView? {
        get {
            return self.okButton
        }
    }

    //MARK: LifeCycle

    override func layoutSubviews() {
        super.layoutSubviews()
        self.okButton.setBtnProperties(self.okButton)
        textView.isSelectable = true
        textView.isUserInteractionEnabled = true
        textView.panGestureRecognizer.allowedTouchTypes = [NSNumber(value: UITouch.TouchType.indirect.rawValue)]

       // textView.text = """
//<form action="/action_page.php">
//          <label for="username">Username: </label>
//          <input type="text" id="username" name="username"><br>
//          <input type="submit" value="Submit">
//        </form>
//""".htmlToString

    }


    //MARK: Actions

    @IBAction func buttonActions(_ sender: UIButton) {
        self.delegate?.callAction(sender.tag)
    }
}
