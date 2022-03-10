/*
 Copyright (C) 2017 Apple Inc. All Rights Reserved.
 See LICENSE.txt for this sample’s licensing information

 Abstract:
 A `UICollectionViewCell` subclass used to display `DataItem`s within `UICollectionView`s.
 */

import UIKit

class DataItemCollectionViewCell: UICollectionViewCell {
    // MARK: Properties
    
    static let reuseIdentifier = "DataItemCell"
    var representedDataItem: ChannelArray?
    
    @IBOutlet weak var channelID: UILabel!
    @IBOutlet weak var channelNameLbl: UILabel!
    @IBOutlet weak var imageView: UIImageView!
    @IBOutlet weak var content:UIView!


    

    
    // MARK: Initialization
    
    override func awakeFromNib() {
        super.awakeFromNib()

        // These properties are also exposed in Interface Builder.
        //imageView.adjustsImageWhenAncestorFocused = true
        // imageView.clipsToBounds = false
        
      //  channelNameLbl.alpha = 1.0
        self.content.layer.cornerRadius = 8.0
        self.content.borderWidth = 4.0
        self.content.borderColor = .gray
        self.content.backgroundColor = .clear//UIColor.gray.withAlphaComponent(0.9)

    }
    
    func setData(_ room: Room) {
        channelNameLbl.text = room.hotel_room
        channelID.text = "\(room.id ?? 0)"
    }
    
    // MARK: UICollectionReusableView
    
    override func prepareForReuse() {
        super.prepareForReuse()
        
        // Reset the label's alpha value so it's initially hidden.
        self.content.borderWidth = 4.0
        self.content.borderColor = .gray
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
                self.content.borderWidth = 6.0
                self.content.borderColor = UIColor.white
            }
            else {
                self.content.borderWidth = 4.0
                self.content.borderColor = UIColor.gray
            }
            self.content.layer.backgroundColor = UIColor.clear.cgColor
        }, completion: nil)
    }
}
