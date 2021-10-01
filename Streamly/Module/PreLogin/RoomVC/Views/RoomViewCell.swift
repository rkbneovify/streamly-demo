//
//  RoomViewCell.swift
//  Streamly
//
//  Created by Aditya Joshi on 01/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class RoomViewCell: UICollectionViewCell {
    
    @IBOutlet weak var channelNameLbl: UILabel!
    @IBOutlet weak var imageView: UIImageView!
    @IBOutlet weak var content:UIView!
    
    static let identifier = String(describing: RoomViewCell.self)
    // MARK: Initialization
    override func awakeFromNib() {
        super.awakeFromNib()
        
        // These properties are also exposed in Interface Builder.
        //imageView.adjustsImageWhenAncestorFocused = true
        // imageView.clipsToBounds = false
        
        //  channelNameLbl.alpha = 1.0
        self.content.layer.cornerRadius = 8.0
        self.content.borderWidth = 0.0
        //self.content.backgroundColor = UIColor.gray.withAlphaComponent(0.9)
        
    }
    
    func setData(_ room: Room) {
        channelNameLbl.text = room.hotel_room
    }
    // MARK: UICollectionReusableView
    
    override func prepareForReuse() {
        super.prepareForReuse()
        
        // Reset the label's alpha value so it's initially hidden.
        self.content.borderWidth = 0.0
    }
    
    // MARK: UIFocusEnvironment
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        /*
         Update the label's alpha value using the `UIFocusAnimationCoordinator`.
         This will ensure all animations run alongside each other when the focus
         changes.
         */
        coordinator.addCoordinatedAnimations({
            if self.isFocused {
                self.content.borderWidth = 4.0
                self.content.layer.borderColor = UIColor.white.cgColor
            }
            else {
                self.content.borderWidth = 0.0
                self.content.layer.borderColor = UIColor.clear.cgColor
            }
        }, completion: nil)
    }
}

